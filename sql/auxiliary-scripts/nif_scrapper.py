import json
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By

driver = webdriver.Firefox()
driver.get("https://nif.marcosantos.me/?i=2")

res = ''

for x in range(50):
    nif = driver.find_element(By.ID, "nif").text
    res += f"\"{nif}\","
    driver.refresh()


with open('nif.txt', 'w') as convert_file:
    convert_file.write(res)

driver.close()
