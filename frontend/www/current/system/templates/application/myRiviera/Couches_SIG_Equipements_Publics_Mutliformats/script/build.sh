#! /bin/bash

# build json
rm build/result.json
cd ..
for f in $(find *.shp)
do
	ogr2ogr -f "GeoJSON" script/${f//.shp/}.json $f ${f//.shp/}
done
cd script

# rename the json files
for f in $(find *.json)
do
	mv $f build/details/${f//EPSG*/}
done
for f in $(find *.json)
do
	mv $f build/details/${f//epsg*/}
done

# create the result concatenation
touch build/tmp.json
echo "[" >> build/tmp.json
for f in $(find build/details/*.json)
do
	cat $f >> build/tmp.json
	echo "," >> build/tmp.json
done
sed '$s/.$//' build/tmp.json > build/result.json
echo "]" >> build/result.json

rm *.json
rm build/tmp.json
