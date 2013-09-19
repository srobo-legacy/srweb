#!/bin/sh

if [ -z "$1" ]
then
	echo "Usage: $0 ROOT_URI"
	echo "  ROOT_URI: the web root to crawl."
	exit 1
fi

ROOT_URI=$1

linkchecker "$ROOT_URI" -o xml -v \
	--ignore-url="$ROOT_URI/(ide|forums|tickets|userman|search.php)" \
	--ignore-url="!$ROOT_URI" \
	| python sitemapgen.py - "$ROOT_URI" > sitemap.xml
