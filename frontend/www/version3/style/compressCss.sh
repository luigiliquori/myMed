#!/bin/bash
for file in $(find -name '*.css'); do
	basename=$(basename $file .css)
	if [[ ${basename#*.} != 'min' ]] ;then
		yui-compressor $file 1> "$(dirname $file)/$(basename $file .css).min.css";
	fi
done
