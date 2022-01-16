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


-- FUNCTION 1

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
	is_blocked		boolean NOT NULL DEFAULT FALSE,
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
	attributes	json,
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
	timestamp	                timestamp NOT NULL DEFAULT NOW(),
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
	timestamp	timestamp NOT NULL DEFAULT NOW(),
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
	approval_state  	approval_state NOT NULL DEFAULT 'pending',
	CONSTRAINT "proposed_product_pk" PRIMARY KEY (id),
	CONSTRAINT "proposed_product_shopper_fk" FOREIGN KEY (shopper_id) REFERENCES "authenticated_shopper"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "price_ck" CHECK (price >= 0),
	CONSTRAINT "amount_ck" CHECK (amount > 0)
);

CREATE TABLE "proposed_product_photo" (
    proposed_product_id integer,
    photo_id            integer,
    CONSTRAINT "proposed_product_photo_pk" PRIMARY KEY (proposed_product_id, photo_id),
	CONSTRAINT "proposed_product_photo_fk" FOREIGN KEY (photo_id) REFERENCES "photo"
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT "proposed_product_product_fk" FOREIGN KEY (proposed_product_id) REFERENCES "proposed_product"
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE "notification" (
	id                  		SERIAL,
	shopper                 	integer NOT NULL,
	timestamp	                timestamp NOT NULL DEFAULT NOW(),
    type                        notification_type NOT NULL,
    read                        boolean NOT NULL DEFAULT FALSE,
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

    CONSTRAINT "review_id_ck" CHECK((type = 'review_vote' AND review_id IS NOT NULL)
								OR (type = 'review_management' AND review_mng_notif_type = 'removed' AND review_id IS NULL)
								OR (type = 'review_management' AND review_mng_notif_type = 'edited' AND review_id IS NOT NULL)
                                OR (type != 'review_management' AND type != 'review_vote' AND review_id IS NULL) ),

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

CREATE OR REPLACE VIEW user_shopper AS SELECT user.id AS id, name, email, phone_number, nif, newsletter_subcribed FROM user join authenticated_shopper ON (users.id = authenticated_shopper.id)

-- INDEX 1
CREATE INDEX shopper_cart ON "product_cart" USING hash (shopper_id);

-- INDEX 2
CREATE INDEX review_score ON "review" USING btree (score);

-- INDEX 3
CREATE INDEX products_of_category ON "product_category" USING btree (category_id);
CLUSTER "product_category" USING products_of_category;

CREATE INDEX shopper_orders ON "order" USING hash (shopper_id);

CREATE INDEX shopper_wishlist ON "wishlist" USING hash (shopper_id);


--- FULL TEXT SEARCH INDEX

ALTER TABLE product
ADD COLUMN tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION product_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
            NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.name), 'A') ||
            setweight(to_tsvector('english', NEW.description), 'B') ||
            setweight(to_tsvector('english', (SELECT string_agg(value, ' ') FROM json_array_elements_text(NEW.attributes -> 'material'))), 'C') ||
            setweight(to_tsvector('english', (SELECT string_agg(value, ' ') FROM json_array_elements_text(NEW.attributes -> 'color'))), 'D')
            );
    END IF;
    IF TG_OP = 'UPDATE' THEN
            IF (NEW.name <> OLD.name OR
                NEW.description <> OLD.description OR
                (SELECT string_agg(value, ' ') FROM json_array_elements_text(NEW.attributes -> 'material'))
                    <> (SELECT string_agg(value, ' ') FROM json_array_elements_text(OLD.attributes -> 'material')) OR
                (SELECT string_agg(value, ' ') FROM json_array_elements_text(NEW.attributes -> 'color'))
                    <> (SELECT string_agg(value, ' ') FROM json_array_elements_text(NEW.attributes -> 'color'))) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.name), 'A') ||
                setweight(to_tsvector('english', NEW.description), 'B') ||
                setweight(to_tsvector('english', (SELECT string_agg(value, ' ') FROM json_array_elements_text(NEW.attributes -> 'material'))), 'C') ||
            setweight(to_tsvector('english', (SELECT string_agg(value, ' ') FROM json_array_elements_text(NEW.attributes -> 'color'))), 'D')
            );
            END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER product_search_update
BEFORE INSERT OR UPDATE ON product
FOR EACH ROW
EXECUTE PROCEDURE product_search_update();


CREATE INDEX product_search_idx ON product USING GIN (tsvectors);







-- TRIGGERS AND FUNCTIONS

-- TRIGGER 1

CREATE OR REPLACE FUNCTION  admin_not_shopper_ck() RETURNS TRIGGER AS $admin_not_shopper_ck$
	BEGIN
		IF EXISTS (
			SELECT *
			FROM "user"
			WHERE "user".id = NEW.id AND is_admin = True
		) THEN
			RAISE EXCEPTION 'An admin cannot be an authenticated shopper.';
		END IF;
		RETURN NEW;
	END
$admin_not_shopper_ck$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS admin_not_shopper_ck ON "authenticated_shopper";
CREATE TRIGGER admin_not_shopper_ck
BEFORE INSERT OR UPDATE ON "authenticated_shopper"
FOR EACH ROW
EXECUTE PROCEDURE admin_not_shopper_ck();


-- TRIGGER 2

CREATE OR REPLACE FUNCTION is_admin_not_updated() RETURNS TRIGGER AS $is_admin_not_updated$
	BEGIN
		IF NEW.is_admin <> OLD.is_admin THEN
			RAISE EXCEPTION 'The "is_admin" parameter cannot be changed';
		END IF;
		RETURN NEW;
	END
$is_admin_not_updated$
LANGUAGE plpgsql;

CREATE TRIGGER is_admin_not_updated
BEFORE UPDATE ON "user"
FOR EACH ROW
EXECUTE PROCEDURE is_admin_not_updated();


-- TRIGGER 3

CREATE OR REPLACE FUNCTION  delete_user_info() RETURNS TRIGGER AS $delete_user_info$
	BEGIN
		UPDATE "user"
		SET name = 'deleted', password = 'deleted', photo_id = DEFAULT, is_deleted = True
		WHERE OLD.id = "user".id;

		IF EXISTS (SELECT * FROM "authenticated_shopper" WHERE id = OLD.ID) THEN
			UPDATE "authenticated_shopper"
			SET "about_me" = 'deleted', "phone_number" = NULL, "nif" = NULL, "newsletter_subcribed" = DEFAULT
			WHERE id = OLD.id;
		END IF;

		RETURN NULL;
	END
$delete_user_info$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS delete_user_info ON "user";
CREATE TRIGGER delete_user_info
BEFORE DELETE ON "user"
FOR EACH ROW
EXECUTE PROCEDURE delete_user_info();


-- TRIGGER 4

CREATE OR REPLACE FUNCTION  check_if_bought_to_review() RETURNS TRIGGER AS $check_if_bought_to_review$
	BEGIN
		IF NOT EXISTS ( SELECT *
				   FROM "order"
				   LEFT JOIN "order_product_amount" ON "order".id = "order_product_amount".order_id
				   WHERE shopper_id = NEW.creator_id AND product_id = NEW.product_id
		) THEN
			RAISE EXCEPTION 'This user has not yet bought the reviewed product.';
		END IF;
		RETURN NEW;
	END
$check_if_bought_to_review$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_if_bought_to_review ON "review";
CREATE TRIGGER check_if_bought_to_review
BEFORE INSERT OR UPDATE ON "review"
FOR EACH ROW
EXECUTE PROCEDURE check_if_bought_to_review();


-- TRIGGER 5

CREATE OR REPLACE FUNCTION  update_prod_avg_stars() RETURNS TRIGGER AS $update_prod_avg_stars$
	BEGIN
		UPDATE "product"
		SET avg_stars = (SELECT CAST(SUM(stars) AS float)
						 FROM "review"
						 WHERE "review".product_id = "product".id)
						 /
						 (SELECT CAST(COUNT(id) AS float)
						 FROM "review"
						 WHERE "review".product_id = "product".id)
		WHERE "product".id = NEW.product_id;
		RETURN NULL;
	END
$update_prod_avg_stars$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS update_prod_avg_stars ON "review";
CREATE TRIGGER update_prod_avg_stars
AFTER INSERT OR UPDATE OR DELETE ON "review"
FOR EACH ROW
EXECUTE PROCEDURE update_prod_avg_stars();


-- TRIGGER 6

CREATE OR REPLACE FUNCTION  review_vote_updater() RETURNS TRIGGER AS $review_vote_updater$
	BEGIN
		 IF (TG_OP = 'DELETE') THEN
            UPDATE "review"
			SET score =
				CASE OLD.vote
					WHEN 'upvote' THEN score - 1
					WHEN 'downvote' THEN score + 1
				END
			WHERE "review".id = OLD.review_id;
		ELSIF (TG_OP = 'INSERT') THEN
            UPDATE "review"
			SET score =
				CASE NEW.vote
					WHEN 'upvote' THEN score + 1
					WHEN 'downvote' THEN score - 1
				END
			WHERE "review".id = NEW.review_id;
        ELSIF (TG_OP = 'UPDATE') THEN
			IF NEW.vote <> OLD.vote THEN
				UPDATE "review"
				SET score =
					CASE NEW.vote
						WHEN 'upvote' THEN score + 2
						WHEN 'downvote' THEN score - 2
					END
				WHERE "review".id = NEW.review_id;
			END IF;
        END IF;
        RETURN NULL;
	END
$review_vote_updater$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS review_vote_updater ON "review_vote";
CREATE TRIGGER review_vote_updater
AFTER INSERT OR DELETE OR UPDATE ON "review_vote"
FOR EACH ROW
EXECUTE PROCEDURE review_vote_updater();


-- TRIGGER 7

CREATE OR REPLACE FUNCTION  update_to_paid() RETURNS TRIGGER AS $update_to_paid$
	BEGIN
		UPDATE "order"
		SET status = 'paid'
		WHERE id = NEW.order_id;
		RETURN NULL;
	END
$update_to_paid$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS update_to_paid ON "payment";
CREATE TRIGGER update_to_paid
AFTER INSERT ON "payment"
FOR EACH ROW
EXECUTE PROCEDURE update_to_paid();


-- TRIGGER 8

CREATE OR REPLACE FUNCTION  order_status_notif() RETURNS TRIGGER AS $order_status_notif$
	BEGIN
		IF (TG_OP = 'INSERT' OR (NEW.status <> OLD.status)) THEN
			INSERT INTO "notification" (shopper, type, order_id, order_notif_type)
			VALUES (NEW.shopper_id, 'order', NEW.id, NEW.status);
		END IF;

		RETURN NEW;
	END
$order_status_notif$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS order_status_notif ON "order";
CREATE TRIGGER order_status_notif
AFTER INSERT OR UPDATE ON "order"
FOR EACH ROW
EXECUTE PROCEDURE order_status_notif();


-- TRIGGER 9

CREATE OR REPLACE FUNCTION is_max_depth(cat_id integer, i integer) RETURNS boolean AS $is_max_depth$
		DECLARE
			cur_parent integer;
		BEGIN
            IF cat_id IS NULL THEN
				RETURN FALSE;
			ELSIF i < 0 THEN
				RETURN TRUE;
			ELSE
				cur_parent := (SELECT parent_category
							   FROM "category"
							   WHERE "category".id = cat_id);
				RETURN is_max_depth(cur_parent, i - 1);
			END IF;
        END
$is_max_depth$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION  category_max_depth() RETURNS TRIGGER AS $category_max_depth$
	DECLARE
		max_depth CONSTANT INTEGER := 2;
	BEGIN
		IF (is_max_depth(NEW.parent_category, max_depth)) THEN
			IF (TG_OP = 'INSERT') THEN
				RAISE EXCEPTION 'The category depth has been exceeded.';
			ELSIF (TG_OP = 'UPDATE') THEN
				UPDATE "category" SET parent_category = OLD.parent_category WHERE id = NEW.id;
				RAISE NOTICE 'The category depth has been exceeded.';
			END IF;
		END IF;
		RETURN NEW;
	END
$category_max_depth$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS category_max_depth ON "category";
CREATE TRIGGER category_max_depth
AFTER INSERT OR UPDATE ON "category"
FOR EACH ROW
EXECUTE PROCEDURE category_max_depth();


-- TRIGGER 10

CREATE OR REPLACE FUNCTION  vote_not_self() RETURNS TRIGGER AS $vote_not_self$
	BEGIN
		IF ((SELECT creator_id
			 FROM "review"
			 WHERE "review".id = NEW.review_id) = NEW.voter_id) THEN
			 RAISE EXCEPTION 'A user cannot vote on its own review.';
		END IF;
		RETURN NEW;
	END
$vote_not_self$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS vote_not_self ON "review_vote";
CREATE TRIGGER vote_not_self
BEFORE INSERT OR UPDATE ON "review_vote"
FOR EACH ROW
EXECUTE PROCEDURE vote_not_self();

-- Trigger 11

CREATE OR REPLACE FUNCTION  send_notif_chng_approv_state() RETURNS TRIGGER AS $send_notif_chng_approv_state$
	BEGIN
		IF (NEW.approval_state <> OLD.approval_state) THEN
			CALL proposed_prod_approv_state_notif(NEW.shopper_id, NEW.id, NEW.approval_state);
		END IF;
		RETURN NULL;
	END
$send_notif_chng_approv_state$
LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS send_notif_chng_approv_state ON "proposed_product";
CREATE TRIGGER send_notif_chng_approv_state
AFTER UPDATE ON "proposed_product"
FOR EACH ROW
EXECUTE PROCEDURE send_notif_chng_approv_state();



-- PROCEDURE 1

CREATE OR REPLACE PROCEDURE update_account_manage_notif(shopper_id integer, notif_type account_management_notif)  AS $$
	BEGIN
		INSERT INTO notification (shopper, type, account_mng_notif_type) VALUES (shopper_id, 'account', notif_type);
	END;
$$ LANGUAGE plpgsql;


-- PROCEDURE 2

CREATE OR REPLACE PROCEDURE review_manage_notif(shopper_id integer, review integer, notif_type review_management_notif) AS $$
	BEGIN
		INSERT INTO notification (shopper, review_id, type, review_mng_notif_type) VALUES (shopper_id, review, 'review_management', notif_type);
	END;
$$ LANGUAGE plpgsql;


-- PROCEDURE 3

CREATE OR REPLACE PROCEDURE proposed_prod_approv_state_notif(shopper_id integer, proposed_product integer, notif_type approval_state) AS $$
	BEGIN
		INSERT INTO notification (shopper, proposed_product_id, type, proposed_product_notif) VALUES (shopper_id, proposed_product, 'proposed_product', notif_type);
	END;
$$ LANGUAGE plpgsql;


-- PROCEDURE 4

CREATE OR REPLACE PROCEDURE create_order(shopper_id_param integer, address_id_param integer, coupon_id_param integer) AS $$
	DECLARE
		subtotal_calc float;
		total_calc float;
		c_percentage float;
		products_not_in_stock INTEGER;
	BEGIN
		IF (SELECT is_active FROM coupon WHERE coupon.id = coupon_id_param) = FALSE THEN
			RAISE EXCEPTION 'COUPON IS NOT ACTIVE';
		END IF;

		products_not_in_stock := (SELECT count(*)
		FROM (
			SELECT (stock - amount) AS left_stock
			FROM product_cart LEFT JOIN product ON (product_cart.product_id = product.id)
			WHERE product_cart.shopper_id = shopper_id_param
		) AS product_stock
		WHERE left_stock < 0);

		IF (products_not_in_stock > 0) THEN
			RAISE EXCEPTION 'ORDER HAS PRODUCTS WITHOUT STOCK';
			RETURN;
		END IF;

		DROP TABLE IF EXISTS product_stats;
		CREATE TEMPORARY TABLE product_stats AS (
			SELECT product.id AS id, (product.stock - product_cart_t.amount) AS stock, (product.price * product_cart_t.amount) AS price
			FROM (SELECT * FROM product_cart WHERE (shopper_id = shopper_id_param)) AS product_cart_t
			LEFT JOIN product ON (product_cart_t.product_id = product.id)
		);

		subtotal_calc := (
			SELECT SUM(price)
			FROM product_stats
		);

		IF subtotal_calc < (SELECT minimum_cart_value
						   FROM coupon
						   WHERE coupon.id = coupon_id_param) THEN
			RAISE EXCEPTION 'The selected coupon is not valid for this order - the cart value is not minimum required.';
		END IF;

		c_percentage := (
			SELECT percentage
			FROM coupon WHERE (id = coupon_id_param)
		);

		IF c_percentage IS NULL THEN
			total_calc := subtotal_calc;
		ELSE
			total_calc := subtotal_calc - (subtotal_calc * c_percentage);
		END IF;

		INSERT INTO "order" ("shopper_id", "address_id", total, subtotal, coupon_id) VALUES (shopper_id_param, address_id_param, total_calc, subtotal_calc, coupon_id_param);

		UPDATE product
		SET stock = product_stats.stock
		FROM product_stats
		WHERE (product.id = product_stats.id);

		INSERT INTO order_product_amount(order_id, product_id, amount, unit_price) (
			SELECT currval('order_id_seq'), product_id, amount, price
			FROM (SELECT * FROM product_cart WHERE (shopper_id = shopper_id_param)) AS product_cart_t
			LEFT JOIN product ON (product_cart_t.product_id = product.id)
		);

		DELETE FROM product_cart
		WHERE (shopper_id = shopper_id_param);

		DROP TABLE product_stats;
	END;
$$ LANGUAGE plpgsql;
