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