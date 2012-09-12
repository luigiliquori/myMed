#! /bin/bash

# rename filename if needed
IFS=$'\n';for f in `find .`; do file=$(echo $f | tr [:blank:] '_'); [ -e $f ] && [ ! -e $file ] && mv "$f" $file;done;unset IFS
for f in $(find . -name '*.xlsx')
do
	rm -rf $f
done;

# convert into csv
for f in $(find . -name '*.xls')
do
	xls2csv $f > ${f//.xls/}.csv
done;

# remove the jSon files
for f in $(find . -name '*.json')
do
	rm $f
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

# concat the json
touch tmp.json
echo "[" >> tmp.json
for f in $(find . -name '*.json')
do
	if [ "$f" != "./tmp.json" ] 
	then
		cat $f >> tmp.json
		echo "," >> tmp.json
	fi
done
sed '$s/.$//' tmp.json > result.json
echo "]" >> result.json
rm tmp.json
