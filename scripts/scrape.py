import os
import time
import json
import copy
import re
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.common.by import By

options = webdriver.ChromeOptions()
options.add_argument("--headless")
driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)

# 今期アニメをスクレイピング
num_month = {'01' : 1,'02' : 1, '03' : 2, '04' : 2, '05' : 2, '06' : 3, '07' : 3, '08' : 3, '09' : 4, '10' : 4, '11' : 4, '12' : 1, '1000' : 1000}
anime_list = []
url = 'https://cal.syoboi.jp/list?cat=1'
driver.get(url)
anime_trs =  driver.find_elements(By.XPATH, "//table[@id='TitleList']/tbody/tr")
anime_urls = []
for anime_tr in anime_trs:
  title = anime_tr.find_element(By.CLASS_NAME, "title").text
  tid = anime_tr.find_element(By.CLASS_NAME, "tid").text
  day = anime_tr.find_element(By.CLASS_NAME, "firstStart").text.split('-')
  anime_url_elem =  anime_tr.find_element(By.XPATH, "td[@class='title']/a")
  anime_url = anime_url_elem.get_attribute('href') + "/subtitle"
  if day[0] == "2026" and num_month[day[1] or "1000"] == 1:
    anime_urls.append({"title": title, "tid": tid, "day": day, "anime_url": anime_url})
time.sleep(10)

for anime_url in anime_urls:
  title = anime_url['title']
  tid = anime_url['tid']
  try:
    year = anime_url['day'][0]
    coor = num_month[anime_url['day'][1]]
  except:
    year = 1900
    coor = 1900
  anime_url = anime_url['anime_url']
  driver.get(anime_url)
  time.sleep(10)
  try:
    furigana = driver.find_element(By.XPATH, '//th[text()="よみ"]/following-sibling::td').get_attribute("textContent")
  except:
    furigana = ""
  try:
    public_url = driver.find_element(By.XPATH, '//a[text()="公式"]').get_attribute('href')
  except:
    public_url = ""
  try:
    twitter = driver.find_element(By.XPATH, '//a[text()="Twitter"]').get_attribute('href')
  except:
    twitter = ""
  company_list = []
  # staff_list = []
  try:
    staff_tr_list = driver.find_elements(By.XPATH, '//div[text()="スタッフ"]/following-sibling::table/tbody/tr')
    for staff_tr in staff_tr_list:
      occupations = re.split('[、・]', staff_tr.find_element(By.TAG_NAME, "th").get_attribute("textContent"))
      for occupation in occupations:
        staffs = staff_tr.find_element(By.TAG_NAME, "td").get_attribute("textContent").split('、')
        for staff in staffs:
          # staff_list.append({"name": staff, "occupation": occupation})
          if((occupation == "アニメーション制作") or (occupation == "制作")):
            company_list.append({"name": staff, "occupation": occupation})
  except:
    a = 1
  # episode_list = []
  # episode_tr_list = driver.find_elements(By.XPATH, "//div[@id='tid_subtitle']/table/tbody/tr")
  # last_episode = ""
  # first_episode = ""
  # for i, episode_tr in enumerate(episode_tr_list):
  #   episode = episode_tr.find_elements(By.TAG_NAME, "td")
  #   if (i == 0):
  #     first_episode = episode[0].get_attribute("textContent")
  #   try:
  #     episode_list.append({"number": episode[0].get_attribute("textContent"), "subtitle": episode[1].get_attribute("textContent")})
  #     if (len(episode_tr_list) == i + 1):
  #       last_episode = episode[0].get_attribute("textContent")
  #   except:
  #     a = 1
  # try:
  #   number_of_episode = int(last_episode) - int(first_episode) + 1
  # except:
  #   number_of_episode = ""
  cast_list = []
  try:
    cast_tr_list = driver.find_elements(By.XPATH, '//div[text()="キャスト"]/following-sibling::table/tbody/tr')
    for cast_tr in cast_tr_list:
      character = cast_tr.find_element(By.TAG_NAME, "th").get_attribute("textContent")
      casts = cast_tr.find_element(By.TAG_NAME, "td").get_attribute("textContent").split('、')
      for cast in casts:
        if(cast):
          cast_list.append({"name": cast, "character": character})
  except:
    a = 1

  # anime_list.append({"title": title, "tid": tid, "year": year, "coor": coor, "furigana": furigana, "casts": cast_list, "staffs": staff_list, "episodes": episode_list, "number_of_episode": number_of_episode, 'public_url': public_url, "twitter": twitter, "add": 0})
  anime_list.append({"title": title, "tid": tid, "year": year, "coor": coor, "furigana": furigana, "casts": cast_list, "company_list": company_list, 'public_url': public_url, "twitter": twitter, "media_category": 1})


with open("data/2026_1_anime_list.json", mode='wt', encoding='utf-8') as file:
    json.dump(anime_list, file, ensure_ascii=False, indent=2)

driver.quit()
