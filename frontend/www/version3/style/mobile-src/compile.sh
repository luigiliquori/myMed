#!/bin/bash
for file in $(ls *.css.less); do
	echo "Compile" $file...
	lessc $file ../mobile/$(basename $file .less)
done
cp *.css ../mobile
