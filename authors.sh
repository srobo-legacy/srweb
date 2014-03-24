#!/bin/bash

git shortlog -sne | 
# Ignore BRAIN LOG-FEEDS
	grep -v BRIAN | 
	sort -rn | 
	cut -f 2 > AUTHORS

if [ "$1" == "-e" ]
then
	nano AUTHORS
fi
