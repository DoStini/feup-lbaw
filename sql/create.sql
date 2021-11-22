SET search_path TO lbaw2116;

DROP TYPE IF EXISTS product_state CASCADE;
DROP TYPE IF EXISTS approval_state CASCADE;
DROP TYPE IF EXISTS review_vote_type CASCADE;
DROP TYPE IF EXISTS review_management_notif CASCADE;
DROP TYPE IF EXISTS account_management_notif CASCADE;
DROP TYPE IF EXISTS order_state CASCADE;

CREATE TYPE product_state AS ENUM ('new', 'slightly_damaged', 'damaged', 'raw_material');
CREATE TYPE approval_state AS ENUM ('pending', 'approved', 'rejected');
CREATE TYPE review_vote_type AS ENUM ('upvote', 'downvote');
CREATE TYPE review_management_notif AS ENUM ('edited', 'removed');
CREATE TYPE account_management_notif AS ENUM ('edited', 'blocked');
CREATE TYPE order_state AS ENUM ('created', 'paid', 'processing', 'shipped');

DROP TABLE IF EXISTS "order_update_notification" CASCADE;
DROP TABLE IF EXISTS "account_management_notification" CASCADE;
DROP TABLE IF EXISTS "review_vote_notification" CASCADE;
DROP TABLE IF EXISTS "review_management_notification" CASCADE;
DROP TABLE IF EXISTS "notification" CASCADE;
DROP TABLE IF EXISTS "proposed_product_category" CASCADE;
DROP TABLE IF EXISTS "proposed_product" CASCADE;
DROP TABLE IF EXISTS "product_on_user_wishlist" CASCADE;
DROP TABLE IF EXISTS "product_on_user_cart" CASCADE;
DROP TABLE IF EXISTS "review_vote" CASCADE;
DROP TABLE IF EXISTS "review_photo" CASCADE;
DROP TABLE IF EXISTS "review" CASCADE;
DROP TABLE IF EXISTS "shipment" CASCADE;
DROP TABLE IF EXISTS "bank_payment" CASCADE;
DROP TABLE IF EXISTS "paypal_payment" CASCADE;
DROP TABLE IF EXISTS "order_product_amount" CASCADE;
DROP TABLE IF EXISTS "order" CASCADE;
DROP TABLE IF EXISTS "coupon" CASCADE;
DROP TABLE IF EXISTS "product_photo" CASCADE;
DROP TABLE IF EXISTS "product_category" CASCADE;
DROP TABLE IF EXISTS "product" CASCADE;
DROP TABLE IF EXISTS "authenticated_shopper_address" CASCADE;
DROP TABLE IF EXISTS "address" CASCADE;
DROP TABLE IF EXISTS "category" CASCADE;
DROP TABLE IF EXISTS "authenticated_shopper" CASCADE;
DROP TABLE IF EXISTS "admin" CASCADE;
DROP TABLE IF EXISTS "user" CASCADE;
DROP TABLE IF EXISTS "photo" CASCADE;

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
	id  integer,
	url varchar(100) NOT NULL,
	CONSTRAINT "id_pk" PRIMARY KEY (id)
);

CREATE TABLE "user" (
	id					integer,
	name 				varchar(100) NOT NULL,
	email 				varchar(255) UNIQUE NOT NULL,
	password 			varchar(255) NOT NULL,
	newsletter_subcribe boolean,
	photo_id			integer NOT NULL DEFAULT 1,
	CONSTRAINT "user_pk" PRIMARY KEY (id),
	CONSTRAINT "valid_email_ck" CHECK (email ~ '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+[.][A-Za-z]+$'),
	CONSTRAINT "photo_id_fk" FOREIGN KEY (photo_id) REFERENCES "photo"
);

CREATE TABLE "admin" (
	id	integer,
	CONSTRAINT "admin_pk" PRIMARY KEY (id),
	CONSTRAINT "admin_fk" FOREIGN KEY (id) REFERENCES "user"
);

CREATE TABLE "authenticated_shopper" (
	id				integer,
	about_me 		varchar(255),
	phone_number	varchar(9),
	nif				varchar(9),
	CONSTRAINT "authenticated_shopper_pk" PRIMARY KEY (id),
	CONSTRAINT "authenticated_shopper_fk" FOREIGN KEY (id) REFERENCES "user",
	CONSTRAINT "valid_phone_number_ck" CHECK (is_number(phone_number) AND LENGTH(phone_number) = 9),
	CONSTRAINT "valid_nif_ck" CHECK (check_nif(nif) != '')
);

CREATE TABLE "category" (
	id				integer,
	name			varchar(100) NOT NULL,
	parent_category integer DEFAULT NULL,
	CONSTRAINT "category_pk" PRIMARY KEY (id),
	CONSTRAINT "c_parent_category_fk" FOREIGN KEY (parent_category) REFERENCES "category"
);

CREATE TABLE "address" (
	id				integer,
	street			varchar(255) NOT NULL,
	postalcode		varchar(8) NOT NULL,
	door			varchar(10) NOT NULL,
	CONSTRAINT "address_pk" PRIMARY KEY (id)
	/* add string "nnnn-nnn" check for postalcode */
);

CREATE TABLE "authenticated_shopper_address" (
	user_id		integer,
	address_id	integer,
	CONSTRAINT "authenticated_shopper_address_pk" PRIMARY KEY (user_id, address_id),
	CONSTRAINT "asa_user_fk" FOREIGN KEY (user_id) REFERENCES "user",
	CONSTRAINT "asa_address_fk" FOREIGN KEY (address_id) REFERENCES "address"
);

CREATE TABLE "product" (
	id			integer,
	name		varchar(100) NOT NULL,
	attributes	varchar(255),
	stock		integer NOT NULL,
	description	varchar(255),
	price		float NOT NULL,
	CONSTRAINT "product_pk" PRIMARY KEY (id),
	CONSTRAINT "product_stock_ck" CHECK (stock >= 0),
	CONSTRAINT "product_price_ck" CHECK (price >= 0)
);

CREATE TABLE "product_category" (
	product_id	integer,
	category_id	integer,
	CONSTRAINT "product_category_pk" PRIMARY KEY (product_id, category_id),
	CONSTRAINT "pc_product_fk" FOREIGN KEY (product_id) REFERENCES "product",
	CONSTRAINT "pc_category_fk" FOREIGN KEY (category_id) REFERENCES "category"
);

CREATE TABLE "product_photo" (
	product_id	integer,
	photo_id	integer,
	CONSTRAINT "product_photo_pk" PRIMARY KEY (product_id, photo_id),
	CONSTRAINT "pp_product_fk" FOREIGN KEY (product_id) REFERENCES "product",
	CONSTRAINT "pp_photo_fk" FOREIGN KEY (photo_id) REFERENCES "photo"
);

CREATE TABLE "coupon" (
	id					integer,
	code				varchar(20) NOT NULL,
	percentage			float NOT NULL,
	minimum_cart_value	float NOT NULL,
	CONSTRAINT "coupon_pk" PRIMARY KEY (id),
	CONSTRAINT "coupon_percentage_ck" CHECK (percentage > 0 AND percentage <= 1),
	CONSTRAINT "coupon_minimum_cart_value_ck" CHECK (minimum_cart_value > 0)
);

CREATE TABLE "order" (
	id							integer,
	authenticated_shopper_id	integer NOT NULL,
	total						float,
	subtotal					float,
	status						order_state NOT NULL DEFAULT 'created',
	applied_coupon_id			integer,
	CONSTRAINT "order_pk" PRIMARY KEY (id),
	CONSTRAINT "o_a_shopper_fk" FOREIGN KEY (authenticated_shopper_id) REFERENCES "authenticated_shopper",
	CONSTRAINT "total_ck" CHECK (total >= 0),
	CONSTRAINT "subtotal_ck" CHECK (subtotal >= 0 AND subtotal >= total),
	CONSTRAINT "o_applied_coupon_fk" FOREIGN KEY (applied_coupon_id) REFERENCES "coupon"
);

CREATE TABLE "order_product_amount" (
	order_id		integer,
	product_id		integer,
	amount			integer NOT NULL,
	unit_price		float NOT NULL,
	CONSTRAINT "order_product_amount_pk" PRIMARY KEY (order_id, product_id),
	CONSTRAINT "opa_order_fk" FOREIGN KEY (order_id) REFERENCES "order",
	CONSTRAINT "opa_product_fk" FOREIGN KEY (product_id) REFERENCES "product",
	CONSTRAINT "amount_ck" CHECK (amount > 0),
	CONSTRAINT "unit_price_ck" CHECK (unit_price >= 0)
);

CREATE TABLE "paypal_payment" (
	order_id				integer,
	paypal_transaction_id	integer UNIQUE NOT NULL,
	value					float NOT NULL,
	CONSTRAINT "paypal_payment_pk" PRIMARY KEY (order_id),
	CONSTRAINT "paypalp_order_fk" FOREIGN KEY (order_id) REFERENCES "order",
	CONSTRAINT "value_ck" CHECK (value >= 0)
);

CREATE TABLE "bank_payment" (
	order_id				integer,
	reference				integer NOT NULL,
	entity					integer NOT NULL,
	value					float NOT NULL,
	CONSTRAINT "bank_payment_pk" PRIMARY KEY (order_id),
	CONSTRAINT "bp_order_fk" FOREIGN KEY (order_id) REFERENCES "order",
	CONSTRAINT "value_ck" CHECK (value >= 0)
	/* fazer checks para referencia e entidade */
);

CREATE TABLE "shipment" (
	order_id				integer,
	address_id				integer NOT NULL,
	cost					float NOT NULL,
	CONSTRAINT "shipment_pk" PRIMARY KEY (order_id),
	CONSTRAINT "s_order_fk" FOREIGN KEY (order_id) REFERENCES "order",
	CONSTRAINT "s_address_fk" FOREIGN KEY (address_id) REFERENCES "address",
	CONSTRAINT "cost_ck" CHECK (cost >= 0)
);

CREATE TABLE "review" (
	id			integer,
	timestamp	date NOT NULL DEFAULT NOW(),
	stars		integer NOT NULL,
	text		varchar(255),
	score		integer NOT NULL DEFAULT 0,
	product_id	integer NOT NULL,
	creator_id	integer NOT NULL,
	CONSTRAINT "review_pk" PRIMARY KEY (id),
	CONSTRAINT "timestamp_ck" CHECK (timestamp <= NOW()),
	CONSTRAINT "stars_ck" CHECK (stars >= 0 AND stars <= 5),
	CONSTRAINT "r_product_fk" FOREIGN KEY (product_id) REFERENCES "product",
	CONSTRAINT "r_creator_fk" FOREIGN KEY (creator_id) REFERENCES "authenticated_shopper"
);

CREATE TABLE "review_photo" (
	review_id	integer,
	photo_id	integer,
	CONSTRAINT "review_photo_pk" PRIMARY KEY (review_id, photo_id),
	CONSTRAINT "rp_review_fk" FOREIGN KEY (review_id) REFERENCES "review",
	CONSTRAINT "rp_photo_fk" FOREIGN KEY (photo_id) REFERENCES "photo"
);

CREATE TABLE "review_vote" (
	voter_id	integer,
	review_id	integer,
	vote		review_vote_type,
	CONSTRAINT "review_vote_pk" PRIMARY KEY (voter_id, review_id),
	CONSTRAINT "rv_voter_fk" FOREIGN KEY (voter_id) REFERENCES "authenticated_shopper",
	CONSTRAINT "rv_review_fk" FOREIGN KEY (review_id) REFERENCES "review"
);

CREATE TABLE "product_on_user_cart" (
	user_id 	integer,
	product_id	integer,
	amount		integer NOT NULL,
	CONSTRAINT "product_on_user_cart_pk" PRIMARY KEY (user_id, product_id),
	CONSTRAINT "pouc_user_fk" FOREIGN KEY (user_id) REFERENCES "user",
	CONSTRAINT "pouc_product_fk" FOREIGN KEY (product_id) REFERENCES "product",
	CONSTRAINT "pouc_amount_ck" CHECK (amount > 0)
);

CREATE TABLE "product_on_user_wishlist" (
	user_id 	integer,
	product_id	integer,
	CONSTRAINT "product_on_user_wishlist_pk" PRIMARY KEY (user_id, product_id),
	CONSTRAINT "pouw_user_fk" FOREIGN KEY (user_id) REFERENCES "user",
	CONSTRAINT "pouw_product_fk" FOREIGN KEY (product_id) REFERENCES "product"
);

CREATE TABLE "proposed_product" (
	id					integer,
	name				varchar(50) NOT NULL,
	price				float NOT NULL,
	amount				integer NOT NULL,
	description			varchar(255) NOT NULL,
	product_cur_state	product_state NOT NULL,
	approval_cur_state	approval_state NOT NULL,
	CONSTRAINT "proposed_product_pk" PRIMARY KEY (id),
	CONSTRAINT "price_ck" CHECK (price >= 0),
	CONSTRAINT "amount_ck" CHECK (amount > 0)
);

CREATE TABLE "proposed_product_category" (
	product_id	integer,
	category_id	integer,
	CONSTRAINT "proposed_product_category_pk" PRIMARY KEY (product_id, category_id),
	CONSTRAINT "ppc_product_fk" FOREIGN KEY (product_id) REFERENCES "proposed_product",
	CONSTRAINT "ppc_category_fk" FOREIGN KEY (category_id) REFERENCES "category"
);

CREATE TABLE "notification" (
	id		integer,
	shopper	integer NOT NULL, 
	CONSTRAINT "notification_pk" PRIMARY KEY (id),
	CONSTRAINT "n_shopper_fk" FOREIGN KEY (shopper) REFERENCES "authenticated_shopper"
	
);

CREATE TABLE "review_management_notification" (
	id					integer,
	review_id			integer NOT NULL,
	notification_type	review_management_notif NOT NULL,
	CONSTRAINT "review_management_notification_pk" PRIMARY KEY (id),
	CONSTRAINT "rmn_review_fk" FOREIGN KEY (review_id) REFERENCES "review"
);

CREATE TABLE "review_vote_notification" (
	id					integer,
	review_id			integer NOT NULL,
	notification_type	review_vote_type NOT NULL,
	CONSTRAINT "review_vote_notification_pk" PRIMARY KEY (id),
	CONSTRAINT "rvn_review_fk" FOREIGN KEY (review_id) REFERENCES "review"
);

CREATE TABLE "account_management_notification" (
	id					integer,
	notification_type	account_management_notif NOT NULL,
	CONSTRAINT "account_management_notification_pk" PRIMARY KEY (id)
);

CREATE TABLE "order_update_notification" (
	id					integer,
	order_id			integer NOT NULL,
	notification_type	order_state NOT NULL,
	CONSTRAINT "order_update_notification_pk" PRIMARY KEY (id),
	CONSTRAINT "oun_order_fk" FOREIGN KEY (order_id) REFERENCES "order"
);