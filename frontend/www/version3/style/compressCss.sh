#!/bin/bash
for file in $(find -name '*.css'); do
	basename=$(basename $file .css)
	basenamenomin=$(basename $basename .min)
	if [[ $basename == $basenamenomin ]] ;then
		yui-compressor $file 1> "$(dirname $file)/$(basename $file .css).min.css";
	fi
done
