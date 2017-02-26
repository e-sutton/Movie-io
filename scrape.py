from bs4 import BeautifulSoup
import requests
import sys
import json

search_terms = sys.argv[1]

url = "http://www.imdb.com/find?ref_=nv_sr_fn&q=" + search_terms + "&s=all"

def find_page_url(url):
    headers = {'User-Agent': 'Mozilla/5.0'}
    response = requests.get(url, headers=headers)
    soup = BeautifulSoup(response.text, "html.parser")

    next_page = soup.find('td', 'result_text').find('a').get('href')

    return next_page


movie_page = find_page_url(url)

final_page = "http://www.imdb.com" + movie_page



def get_movie_data(movie_page):
    headers = {'User-Agent': 'Mozilla/5.0'}
    response = requests.get(movie_page, headers=headers)
    soup = BeautifulSoup(response.text, "html.parser")

    movie_title = soup.find('h1', {'itemprop': 'name'}).get_text().strip()
    release_date = soup.find('div', {'class': 'subtext'}).find('a',{'title':'See more release dates'}).get_text().strip()
    plot = soup.find('div', {'class': 'inline canwrap'}).find('p').get_text().strip()
    actor1 = soup.find('span', {'itemprop': 'actors'}).find('a').find('span').get_text().strip()
    actor2 = soup.find('span', {'itemprop': 'actors'}).find_next('span', {'itemprop': 'actors'}).find('a').find('span').get_text().strip()
    actor3 = soup.find('span', {'itemprop': 'actors'}).find_next('span', {'itemprop': 'actors'}).find_next('span', {'itemprop': 'actors'}).find('a').find('span').get_text().strip()
    director = soup.find('span', {'itemprop': 'director'}).find('a').find('span').get_text().strip()
    awards = soup.find('span', {'itemprop': 'awards'}).get_text().strip()
    awards2 = soup.find('span', {'itemprop': 'awards'}).find_next('span', {'itemprop': 'awards'}).get_text().strip()
    awardsFinal = awards + " " + awards2
    metascore = soup.find('div',{'class': 'metacriticScore'}).find('span').get_text().strip()
    poster = soup.find('div',{'class': 'poster'}).find('a').find('img')['src'].strip()

    results = "{'title': '" + movie_title + "', 'releasedate': '" + release_date + "', 'plot': '" + plot + "', 'actors': '" + actor1 + "," + actor2 + "," + actor3 + "', 'director': '" + director + "', 'awards': '" +awardsFinal + "' 'metascore': '" + metascore + "' 'posterlink': '" + poster + "'}"
    return json.dumps(results)

print(get_movie_data(final_page))
