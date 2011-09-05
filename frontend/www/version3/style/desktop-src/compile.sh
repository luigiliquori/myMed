#!/bin/bash
for file in $(ls *.css.less); do
	echo "Compile" $file...
	lessc $file ../desktop/$(basename $file .less)
done
cp *.css ../desktop
