#!/usr/bin/env python

import sys

if len(sys.argv) < 3:
    print "Usage: sitemapgen.py linkchecker-out searched-root"
    print " linkchecker-out: The xml output of running linkchecker over the website."
    print "                  Can be a file, or '-' to read from stdin."
    print "   searched-root: The web address of the searched website."
    print " See createsitemap.sh for example."
    exit(1)

from BeautifulSoup import BeautifulStoneSoup, NavigableString, Tag

SEARCHED_ROOT = sys.argv[2] # eg: 'http://localhost/srweb/'
SITE_ROOT = 'https://www.studentrobotics.org/'

in_file = sys.argv[1]
if in_file != '-':
    xml = open(in_file).read()
else:
    xml = sys.stdin.read()
soup = BeautifulStoneSoup(xml)

emptymap = '<?xml version="1.0" encoding="UTF-8"?>' \
         + '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' \
         + ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' \
         + ' xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' \
         + '  http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' \
         + '</urlset>'

sitemap = BeautifulStoneSoup(emptymap)
urlset = sitemap.contents[1]

parturls = set()

for urldata in soup.findAll('urldata'):
    valid = urldata('valid')[0]
    if str(valid.text) != '1':
        continue

    realurl = urldata('realurl')[0].text
    if not realurl.startswith(SEARCHED_ROOT):
        continue

    parturl = realurl[len(SEARCHED_ROOT):]

    if parturl.startswith("content/") \
    or parturl.startswith("css/") \
    or parturl.startswith("fonts/") \
    or parturl.startswith("forum") \
    or parturl.startswith("icon/") \
    or parturl.startswith("images/"):
        continue

    q_idx = parturl.find('?')
    if q_idx >= 0:
        parturl = parturl[:q_idx]

    parturls.add(parturl)

for parturl in sorted(parturls):

    url = Tag(sitemap, 'url')
    urlset.insert(1, url)
    loc = Tag(sitemap, 'loc')
    url.insert(0, loc)
    text = NavigableString(SITE_ROOT + parturl)
    loc.insert(0, text)

print sitemap.prettify()
