import json
import re
import requests

playlists = json.load(open('remix.json'))

stemsPattern = re.compile('\"downloadPackageUrl\":\"([^\"]+)')

output = open('stems.html', 'w')
print('<html><body>', file=output)

index = 0

for playlist in playlists:
    print('%d: %s' % (index, playlist['title']))
    index += 1
    response = requests.get(playlist['share_url'], headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4122.7 Safari/537.36'})
    if not response.ok:
        print('Status %d: %s' % (response.status_code, playlist['share_url']))
    
    html = response.text

    matches = re.findall(stemsPattern, html)
    if matches is None:
        continue
    for url in matches:
        print('<a href="%s">%s: %s</a><br>' % (url, playlist['ownerFullName'], playlist['title']), file=output)

print('</body></html>', file=output)
output.close()
