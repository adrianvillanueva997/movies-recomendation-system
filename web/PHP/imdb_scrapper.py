import sys
import re
import requests
from bs4 import BeautifulSoup

web_url = str(sys.argv[1])
headers = {
    'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X x.y; rv:42.0) Gecko/20100101 Firefox/42.0'}
request = requests.get(url=web_url, headers=headers)
html = request.content
soup = BeautifulSoup(html, 'html.parser')
div = soup.find('div', {'class': 'poster'})
regex = r'src=".*" '
reg = re.compile(regex)
img_result = reg.search(str(div))
img_result = (img_result.group(0))
img_url = str(img_result).replace('src=', '')
img_url = img_url.replace('\"', '')
print(img_url)
