import dload
import json
from bs4 import BeautifulSoup
import unicodedata
from io import StringIO
import sys

url = "https://www.apt2b.com/products.json?limit=250&page=";
exportableJSON = json.loads('{"products":{}}')
productArray = []

def to_kebab_case(value):
    return "-".join(value.lower().split())

import json
import random

product_id = 1
cat_counter = 1
photo_id = 58

products = []
product_ids = {}
sql_dump = []
corrected_sql_dump = []
categories = {}

def productC(id, name, desc, stock, price, attributes):
    name = name.replace("'", "''")[0:90]
    desc = desc.replace("'", "''")
    if(float(price) == 0): price = format(random.uniform(198.00, 350.00), '.2f')
    attributes = json.dumps(attributes).replace('\'', '"')
    return f"insert into \"product\" (name, description, attributes, stock, price) values ('{name}', '{desc}', \'{attributes}\', {stock}, {price});"

def product_categoryC(category_id, product_id):
    return f'insert into \"product_category\" (product_id, category_id) values ({product_id}, {category_id});'

def photoC(photo, product_id):
    global photo_id

    ph = f"insert into \"photo\" (url) values ('{photo}');"
    assoc = f"insert into \"product_photo\" (product_id, photo_id) values ({product_id}, {photo_id});"
    photo_id += 1

    return ph + "\n" + assoc

for page in range(1, 3):
    if(page == 6): continue
    products = dload.json(url + str(page))
    for product in products["products"]:
        for variant in product["variants"]:
            productJSON = json.loads('{}')
            productJSON["id"] = variant["id"]
            productJSON["title"] = product["title"]
            
            if(not product["body_html"]): product["body_html"] = "From reFurniture essentials collection."
            description = BeautifulSoup(product["body_html"], "html5lib")
            productJSON["description"] =  description.get_text()

            productJSON["product_type"] = product["product_type"]
            productJSON["price"] = variant["price"]
            productJSON["color"] = None if variant["title"] == "Default Title" else variant["title"]

            if(len(product["variants"]) > 1):
                variants = json.loads('{}')
                for otherVariant in product["variants"]:
                    variants[otherVariant["id"]] = to_kebab_case(otherVariant["title"])
                productJSON["variants"] = variants
            else: productJSON["variants"] = []

            productImages = []

            for image in product["images"]:
                newImage = json.loads('{}')
                variantIds = image["variant_ids"]
                if((len(variantIds) > 0 and variantIds[0] == variant["id"]) or len(product["variants"]) == 1):
                    newImage["id"] = image["id"]
                    newImage["product_id"] = variant["id"]
                    newImage["url"] = image["src"]
                    productImages.append(newImage)
            
            productJSON["images"] = productImages
            productArray.append(productJSON)

for item in productArray:

    product_ids[product_id] = item["id"]
    product_id += 1

    if(item["product_type"] not in categories):
        categories[item["product_type"]] = cat_counter
        cat_counter += 1

    attributes = json.loads('{}')
    if(item['color']):
        attributes['color'] = item["color"]
        attributes['variants'] = item["variants"]
    
    sql_dump.append(productC(item["id"], item["title"], item["description"], random.randint(15, 30), item["price"], attributes))

    for ph in item["images"]:
        sql_dump.append(photoC(ph["url"], item["id"]))

    sql_dump.append(product_categoryC(categories[item["product_type"]], item["id"]))

for sql_statement in sql_dump:
    new_statement = sql_statement
    for (real_id, prev_id) in product_ids.items():
        if(sql_statement.find(str(prev_id)) != -1):
            new_statement = new_statement.replace(str(prev_id), str(real_id))
    corrected_sql_dump.append(new_statement)

for sql_statement in corrected_sql_dump:
    print(sql_statement)

for (category_name, id) in categories.items():
    print(f'insert into \"category\" (id, name, parent_category) values ({id},\'{category_name}\', NULL);')