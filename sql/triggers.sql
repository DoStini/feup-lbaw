CREATE OR REPLACE FUNCTION check_stock() RETURNS trigger AS $$
	DECLARE
		products_not_in_stock INTEGER;
	BEGIN
		products_not_in_stock := (SELECT count(*)
		FROM (
			SELECT (stock - amount) AS left_stock
			FROM product_on_user_cart LEFT JOIN product ON (product_on_user_cart.product_id = product.id)
			WHERE product_on_user_cart.user_id = NEW.authenticated_shopper_id
		) AS product_stock
		WHERE left_stock < 0);
		
		IF (products_not_in_stock > 0) THEN
			RAISE EXCEPTION 'ORDER HAS PRODUCTS WITHOUT STOCK';
			RETURN NULL;
		END IF;		
		
		RETURN NEW;
	END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_stock ON "order";
CREATE TRIGGER check_stock BEFORE INSERT
ON "order"
FOR EACH ROW
EXECUTE PROCEDURE check_stock();

--insert into product_on_user_cart (user_id, product_id, amount) values (7, 2, 1);
--insert into product_on_user_cart (user_id, product_id, amount) values (7, 3, 3);

-- update product set stock = 3 where id = 3;

-- select * from product_on_user_cart where user_id = 7;
-- select * from product where id = 1;
-- SELECT count(*)
-- 		FROM (
-- 			SELECT (stock - amount) AS left_stock
-- 			FROM product_on_user_cart LEFT JOIN product ON (product_on_user_cart.product_id = product.id)
-- 			WHERE product_on_user_cart.user_id =  7
-- 		) AS product_stock
-- 		WHERE left_stock < 0;
-- insert into "order"(authenticated_shopper_id, shipment_address, total, subtotal) values (7, 1, 10, 10)