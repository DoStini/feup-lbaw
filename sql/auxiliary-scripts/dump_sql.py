import json
import random

photo_id = 56
product_id = 1

products = []


def product(name, desc, stock, price):
    name = name.replace("'", "''")[0:90]
    desc = desc.replace("'", "''")
    return f"insert into \"product\" (name, description, attributes, stock, price) values ('{name}', '{desc}', '[]', {stock}, {price});"


def photo(photo):
    global photo_id, product_id

    ph = f"insert into \"photo\" (url) values ('{photo}');"
    assoc = f"insert into \"product_photo\" (product_id, photo_id) values ({product_id}, {photo_id});"
    photo_id += 1

    return ph + "\n" + assoc


# Python program to read
# json file


# Opening JSON file
f = open('dataset.json')

# returns JSON object as
# a dictionary
data = json.load(f)

x = 0

for item in data:
    if item["title"] in products:
        continue

    products.append(item["title"])

    x += 1
    if x > 1000:
        break
    print(product(item["title"], item["description"],
          random.randint(15, 30), item["price"]))

    curr = 0
    for ph in item["images_urls"]:
        if curr > 2:
            break
        curr += 1
        print(photo(ph))
    product_id += 1
