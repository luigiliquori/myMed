#! /bin/bash

# rename filename if needed
IFS=$'\n';for f in `find .`; do file=$(echo $f | tr [:blank:] '_'); [ -e $f ] && [ ! -e $file ] && mv "$f" $file;done;unset IFS

# convert into csv
for f in $(find . -name '*.xls')
do
	xls2csv $f > ${f//.xls/}.csv
done;

# convert to json
tmp=$(pwd);
for d in $(find *)
do
	if  [ -d $d ] 
	then
		tmp=$(pwd)
		cd $d
		rm -rf *.json
		for f in $(find . -name "*.csv")
		do
			name=${f//.csv/}
			name=${name//.\//}
			ogr2ogr -f "GeoJSON" $name.json $name.csv $name
		done;
		cd $tmp;
	fi
done;
