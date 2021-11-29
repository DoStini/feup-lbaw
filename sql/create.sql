SET search_path TO lbaw2116;

DROP TYPE IF EXISTS product_state CASCADE;
DROP TYPE IF EXISTS approval_state CASCADE;
DROP TYPE IF EXISTS review_vote_type CASCADE;
DROP TYPE IF EXISTS review_management_notif CASCADE;
DROP TYPE IF EXISTS account_management_notif CASCADE;
DROP TYPE IF EXISTS order_state CASCADE;
DROP TYPE IF EXISTS notification_type CASCADE;

CREATE TYPE product_state AS ENUM ('new', 'slightly_damaged', 'damaged', 'raw_material');
CREATE TYPE approval_state AS ENUM ('pending', 'approved', 'rejected');
CREATE TYPE review_vote_type AS ENUM ('upvote', 'downvote');
CREATE TYPE review_management_notif AS ENUM ('edited', 'removed');
CREATE TYPE account_management_notif AS ENUM ('edited', 'blocked');
CREATE TYPE order_state AS ENUM ('created', 'paid', 'processing', 'shipped');
CREATE TYPE notification_type AS ENUM ('review_vote', 'review_management','account', 'order', 'proposed_product');

DROP TABLE IF EXISTS "photo" CASCADE;
DROP TABLE IF EXISTS "user" CASCADE;
DROP TABLE IF EXISTS "authenticated_shopper" CASCADE;
DROP TABLE IF EXISTS "category" CASCADE;
DROP TABLE IF EXISTS "district" CASCADE;
DROP TABLE IF EXISTS "county" CASCADE;
DROP TABLE IF EXISTS "zip_code" CASCADE;
DROP TABLE IF EXISTS "address" CASCADE;
DROP TABLE IF EXISTS "authenticated_shopper_address" CASCADE;
DROP TABLE IF EXISTS "product" CASCADE;
DROP TABLE IF EXISTS "product_category" CASCADE;
DROP TABLE IF EXISTS "product_photo" CASCADE;
DROP TABLE IF EXISTS "coupon" CASCADE;
DROP TABLE IF EXISTS "order" CASCADE;
DROP TABLE IF EXISTS "order_product_amount" CASCADE;
DROP TABLE IF EXISTS "payment" CASCADE;
DROP TABLE IF EXISTS "review" CASCADE;
DROP TABLE IF EXISTS "review_photo" CASCADE;
DROP TABLE IF EXISTS "review_vote" CASCADE;
DROP TABLE IF EXISTS "product_cart" CASCADE;
DROP TABLE IF EXISTS "wishlist" CASCADE;
DROP TABLE IF EXISTS "proposed_product" CASCADE;
DROP TABLE IF EXISTS "proposed_product_photo" CASCADE;
DROP TABLE IF EXISTS "notification" CASCADE;

DROP FUNCTION IF EXISTS "is_number";
CREATE FUNCTION "is_number" (str varchar(255))
RETURNS boolean
LANGUAGE plpgsql
AS
$$
DECLARE 
	result boolean;

BEGIN

result := str ~ '^[0-9\.]+$';
RETURN result; 

END;
$$;

DROP FUNCTION IF EXISTS "check_nif";
CREATE FUNCTION "check_nif" (nif_input varchar(9))
RETURNS varchar(9)
LANGUAGE plpgsql
AS
$$
DECLARE 
	j int := 9;
	i int := 1;
	total int := 0;
	digit_control int;
	result varchar(9);

BEGIN
 
IF  LENGTH(nif_input) = 9 AND is_number(nif_input) AND CAST(LEFT(nif_input,1) AS integer) NOT IN (0,4) THEN    
    WHILE i < LENGTH(nif_input) LOOP	 
         total := total + CAST(SUBSTRING(nif_input,i,1) AS integer) * j;            
         j := j-1;
         i := i+1;
    END LOOP;          
 
    IF MOD(total, 11) = 0 OR MOD(total, 11) = 1 THEN
         digit_control := 0;         
    ELSE           
         digit_control := 11 - MOD(total, 11); 
	END IF;
 
    IF digit_control = CAST(RIGHT(nif_input,1) AS integer) THEN                              
         result := nif_input;   /* nif válido */       
    ELSE                   
         result := '' ;         /* nif inválido */
	END IF;
ELSE
    result := '';          /* nif inválido */ 
END IF;
RETURN result; 

END;
$$;

CREATE TABLE "photo" (
	id  SERIAL,
	url varchar(100) NOT NULL,
	CONSTRAINT "id_pk" PRIMARY KEY (id)
);

CREATE TABLE "user" (
	id					    SERIAL,
	name 				    varchar(100) NOT NULL,
	email 				    varchar(255) UNIQUE NOT NULL,
	password 			    varchar(255) NOT NULL,
	photo_id			    integer NOT NULL DEFAULT 1,
    is_admin                boolean DEFAULT FALSE,
    is_deleted              boolean NOT NULL DEFAULT FALSE, 
	CONSTRAINT "user_pk" PRIMARY KEY (id),
	CONSTRAINT "photo_id_fk" FOREIGN KEY (photo_id) REFERENCES "photo"
		ON UPDATE CASCADE
		ON DELETE SET DEFAULT,
	CONSTRAINT "valid_email_ck" CHECK (email ~ '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+[.][A-Za-z]+$')
);

CREATE TABLE "authenticated_shopper" (
	id				integer,
	about_me 		varchar,
	phone_number	varchar(9),
	nif				varchar(9),
	newsletter_subcribed    boolean DEFAULT FALSE,
	CONSTRAINT "authenticated_shopper_pk" PRIMARY KEY (id),
	CONSTRAINT "authenticated_shopper_fk" FOREIGN KEY (id) REFERENCES "user"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "valid_phone_number_ck" CHECK (is_number(phone_number) AND LENGTH(phone_number) = 9),
	CONSTRAINT "valid_nif_ck" CHECK (nif IS NULL OR check_nif(nif) != '')
);

CREATE TABLE "category" (
	id				SERIAL,
	name			varchar(100) NOT NULL,
	parent_category integer DEFAULT NULL,
	CONSTRAINT "category_pk" PRIMARY KEY (id),
		CONSTRAINT "c_parent_category_fk" FOREIGN KEY (parent_category) REFERENCES "category"
		ON UPDATE CASCADE 
		ON DELETE SET NULL,
    CONSTRAINT "name_unique" UNIQUE (name)
);

CREATE TABLE district (
    id SERIAL,
    name VARCHAR(64) UNIQUE NOT NULL,
    CONSTRAINT district_pk PRIMARY KEY (id)
);

CREATE TABLE county (
    id SERIAL,
    name VARCHAR(64) NOT NULL,
    district_id INTEGER NOT NULL,
    CONSTRAINT county_pk PRIMARY KEY (id),
    CONSTRAINT county_district_fk FOREIGN KEY (district_id) REFERENCES district
		ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    CONSTRAINT county_name_unique UNIQUE (name, district_id)
);

CREATE TABLE zip_code (
    id SERIAL,
    zip_code VARCHAR(16) UNIQUE NOT NULL,
    county_id INTEGER NOT NULL,
    CONSTRAINT zip_code_pk PRIMARY KEY (id),
    CONSTRAINT zip_code_county_fk FOREIGN KEY (county_id) REFERENCES county
	    ON UPDATE RESTRICT
        ON DELETE RESTRICT
);

CREATE TABLE "address" (
	id				SERIAL,
	street			varchar(255) NOT NULL,
	zip_code		integer NOT NULL,
	door			varchar(10) NOT NULL,
	CONSTRAINT "address_pk" PRIMARY KEY (id),
	CONSTRAINT "zip_code_fk" FOREIGN KEY (zip_code) REFERENCES zip_code
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

CREATE TABLE "authenticated_shopper_address" (
	shopper_id	integer,
	address_id	integer,
	CONSTRAINT "authenticated_shopper_address_pk" PRIMARY KEY (shopper_id, address_id),
	CONSTRAINT "asa_user_fk" FOREIGN KEY (shopper_id) REFERENCES "authenticated_shopper"
			ON UPDATE CASCADE
			ON DELETE CASCADE,
	CONSTRAINT "asa_address_fk" FOREIGN KEY (address_id) REFERENCES "address"
			ON UPDATE CASCADE
			ON DELETE CASCADE
);

CREATE TABLE "product" (
	id			SERIAL,
	name		varchar(100) NOT NULL,
	attributes	varchar(255),
	stock		integer NOT NULL,
	description	varchar(255),
	price		float NOT NULL,
    avg_stars   float NOT NULL DEFAULT 0,
	CONSTRAINT "product_pk" PRIMARY KEY (id),
	CONSTRAINT "product_stock_ck" CHECK (stock >= 0),
	CONSTRAINT "product_price_ck" CHECK (price >= 0)
);

CREATE TABLE "product_category" (
	product_id	integer,
	category_id	integer,
	CONSTRAINT "product_category_pk" PRIMARY KEY (product_id, category_id),
	CONSTRAINT "pc_product_fk" FOREIGN KEY (product_id) REFERENCES "product"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "pc_category_fk" FOREIGN KEY (category_id) REFERENCES "category"
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE "product_photo" (
	product_id	integer,
	photo_id	integer,
	CONSTRAINT "product_photo_pk" PRIMARY KEY (product_id, photo_id),
	CONSTRAINT "pp_product_fk" FOREIGN KEY (product_id) REFERENCES "product"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "pp_photo_fk" FOREIGN KEY (photo_id) REFERENCES "photo"
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE "coupon" (
	id					SERIAL,
	code				varchar(20) NOT NULL,
	percentage			float NOT NULL,
	minimum_cart_value	float NOT NULL,
    is_active              boolean DEFAULT TRUE,
	CONSTRAINT "coupon_pk" PRIMARY KEY (id),
	CONSTRAINT "coupon_percentage_ck" CHECK (percentage > 0 AND percentage <= 1),
	CONSTRAINT "coupon_minimum_cart_value_ck" CHECK (minimum_cart_value > 0)
);

CREATE TABLE "order" (
	id							SERIAL,
	shopper_id              	integer NOT NULL,
    address_id                  integer NOT NULL,
	total						float NOT NULL,
	subtotal					float NOT NULL,
	status						order_state NOT NULL DEFAULT 'created',
	coupon_id       			integer,
	CONSTRAINT "order_pk" PRIMARY KEY (id),
	CONSTRAINT "o_a_shopper_fk" FOREIGN KEY (shopper_id) REFERENCES "authenticated_shopper"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "shipment_address_fk" FOREIGN KEY (address_id) REFERENCES "address"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "o_applied_coupon_fk" FOREIGN KEY (coupon_id) REFERENCES "coupon"
		ON UPDATE CASCADE
		ON DELETE SET NULL,
    CONSTRAINT "total_ck" CHECK (total >= 0),
	CONSTRAINT "subtotal_ck" CHECK (subtotal >= 0 AND subtotal >= total)
);

CREATE TABLE "order_product_amount" (
	order_id		integer,
	product_id		integer,
	amount			integer NOT NULL,
	unit_price		float NOT NULL,
	CONSTRAINT "order_product_amount_pk" PRIMARY KEY (order_id, product_id),
	CONSTRAINT "opa_order_fk" FOREIGN KEY (order_id) REFERENCES "order"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "opa_product_fk" FOREIGN KEY (product_id) REFERENCES "product"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "amount_ck" CHECK (amount > 0),
	CONSTRAINT "unit_price_ck" CHECK (unit_price >= 0)
);

CREATE TABLE "payment" (
	order_id				integer,
	value					float NOT NULL,
	paypal_transaction_id	varchar(19) UNIQUE,
    entity                  integer,
    reference               integer,
    CONSTRAINT "payment_pk" PRIMARY KEY (order_id),
    CONSTRAINT "order_fk"   FOREIGN KEY (order_id) REFERENCES "order"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "payment_ck" CHECK(
            NOT (value <= 0.001 AND paypal_transaction_id IS NULL AND entity IS NULL AND reference IS NULL)
            AND (
                ((entity IS NULL) = (reference IS NULL))
                AND (paypal_transaction_id IS NULL) != (entity IS NULL AND reference IS NULL)
            )
        ),
	CONSTRAINT "value_ck" CHECK (value >= 0),
    CONSTRAINT "paypal_ck" CHECK (NULL OR (LENGTH("paypal_transaction_id") <= 19 AND LENGTH("paypal_transaction_id") >= 17))
);

CREATE TABLE "review" (
	id 			SERIAL,
	timestamp	date NOT NULL DEFAULT NOW(),
	stars		integer NOT NULL,
	text		varchar,
	product_id	integer NOT NULL,
	creator_id	integer NOT NULL,
    score       integer NOT NULL DEFAULT 0,
	CONSTRAINT "review_pk" PRIMARY KEY (id),
		CONSTRAINT "r_product_fk" FOREIGN KEY (product_id) REFERENCES "product"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "r_creator_fk" FOREIGN KEY (creator_id) REFERENCES "authenticated_shopper"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "timestamp_ck" CHECK (timestamp <= NOW()),
	CONSTRAINT "stars_ck" CHECK (stars >= 0 AND stars <= 5)
);

CREATE TABLE "review_photo" (
	review_id	integer,
	photo_id	integer,
	CONSTRAINT "review_photo_pk" PRIMARY KEY (review_id, photo_id),
	CONSTRAINT "rp_review_fk" FOREIGN KEY (review_id) REFERENCES "review"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "rp_photo_fk" FOREIGN KEY (photo_id) REFERENCES "photo"
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE "review_vote" (
	voter_id	integer,
	review_id	integer,
	vote		review_vote_type NOT NULL,
	CONSTRAINT "review_vote_pk" PRIMARY KEY (voter_id, review_id),
	CONSTRAINT "rv_voter_fk" FOREIGN KEY (voter_id) REFERENCES "authenticated_shopper"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "rv_review_fk" FOREIGN KEY (review_id) REFERENCES "review"
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE "product_cart" (
	shopper_id 	integer,
	product_id	integer,
	amount		integer NOT NULL,
	CONSTRAINT "product_cart_pk" PRIMARY KEY (shopper_id, product_id),
	CONSTRAINT "pouc_user_fk" FOREIGN KEY (shopper_id) REFERENCES "authenticated_shopper"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "pouc_product_fk" FOREIGN KEY (product_id) REFERENCES "product"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "pouc_amount_ck" CHECK (amount > 0)
);

CREATE TABLE "wishlist" (
	shopper_id 	integer,
	product_id	integer,
	CONSTRAINT "wishlist_pk" PRIMARY KEY (shopper_id, product_id),
	CONSTRAINT "w_user_fk" FOREIGN KEY (shopper_id) REFERENCES "authenticated_shopper"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "w_product_fk" FOREIGN KEY (product_id) REFERENCES "product"
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE "proposed_product" (
	id					SERIAL,
    shopper_id          integer NOT NULL,
	name				varchar(50) NOT NULL,
	price				float NOT NULL,
	amount				integer NOT NULL,
	description			varchar(255) NOT NULL,
	product_state   	product_state NOT NULL,
	approval_state  	approval_state NOT NULL,
	CONSTRAINT "proposed_product_pk" PRIMARY KEY (id),
	CONSTRAINT "proposed_product_shopper_fk" FOREIGN KEY (shopper_id) REFERENCES "authenticated_shopper",
	CONSTRAINT "price_ck" CHECK (price >= 0),
	CONSTRAINT "amount_ck" CHECK (amount > 0)
);

CREATE TABLE "proposed_product_photo" (
    proposed_product_id integer,
    photo_id            integer,
    CONSTRAINT "proposed_product_photo_pk" PRIMARY KEY (proposed_product_id, photo_id),
	CONSTRAINT "proposed_product_photo_fk" FOREIGN KEY (photo_id) REFERENCES "photo",
	CONSTRAINT "proposed_product_product_fk" FOREIGN KEY (proposed_product_id) REFERENCES "proposed_product"	
);

CREATE TABLE "notification" (
	id                  		SERIAL,
	shopper                 	integer NOT NULL,
	timestamp	                date NOT NULL DEFAULT NOW(),
    type                        notification_type NOT NULL,
    sent                        boolean NOT NULL DEFAULT FALSE,
    visited                     boolean NOT NULL DEFAULT FALSE,
	review_id			        integer,
	order_id			        integer,
	proposed_product_id			integer,
    review_vote_notif_type      review_vote_type,
    review_mng_notif_type       review_management_notif,
    account_mng_notif_type      account_management_notif,
    order_notif_type            order_state,
    proposed_product_notif      approval_state,

	CONSTRAINT "notification_pk" PRIMARY KEY (id),
	CONSTRAINT "n_shopper_fk" FOREIGN KEY (shopper) REFERENCES "authenticated_shopper"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "oun_order_fk" FOREIGN KEY (order_id) REFERENCES "order"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "oun_review_fk" FOREIGN KEY (review_id) REFERENCES "review"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "oun_proposed_prod_fk" FOREIGN KEY (proposed_product_id) REFERENCES "proposed_product"
		ON UPDATE CASCADE
		ON DELETE CASCADE,

    CONSTRAINT "timestamp_ck" CHECK(timestamp <= NOW()),

    CONSTRAINT "review_vote_notif_type_ck" CHECK((type = 'review_vote') = (review_vote_notif_type IS NOT NULL)),

    CONSTRAINT "review_mng_notif_type_ck" CHECK((type = 'review_management') = (review_mng_notif_type IS NOT NULL)),

    CONSTRAINT "account_mng_notif_type_ck" CHECK((type = 'account') = (account_mng_notif_type IS NOT NULL)),

    CONSTRAINT "order_notif_type_ck" CHECK((type = 'order') = (order_notif_type IS NOT NULL)),

    CONSTRAINT "proposed_product_notif_ck" CHECK((type = 'proposed_product') = (proposed_product_notif IS NOT NULL)),

    CONSTRAINT "review_id_ck" CHECK(((type = 'review_management' OR type = 'review_management') AND review_id IS NOT NULL)
                                OR ((type != 'review_management' AND type != 'review_management') AND review_id IS NULL)),

    CONSTRAINT "order_id_ck" CHECK(((type = 'order') AND order_id IS NOT NULL)
                                OR ((type != 'order') AND order_id IS NULL)),

    CONSTRAINT "proposed_product_id" CHECK(((type = 'proposed_product') AND proposed_product_id IS NOT NULL)
                                OR ((type != 'proposed_product') AND proposed_product_id IS NULL)),

    CONSTRAINT "exclusive_notif_ck" CHECK (num_nonnulls(
        review_vote_notif_type,
        review_mng_notif_type,
        account_mng_notif_type,
        order_notif_type,
        proposed_product_notif) = 1)
);
