voted = {}
def check_voter(name):
    if voted.get(name):
        print('kick them out!')
    else:
        voted[name] = True
        print('let them vote!')

#缓存在网页中应用
cache = {}
def check_cache(url):
    if cache.get(url):
        return cache[url]
    else:
        data = get_server(url)
        cache[url] = data
        return data