#! /bin/bash

# build the json files
cd /local/mymed/frontend/www/current/system/templates/application/myRivieraAdmin/Couches_SIG_Equipements_Publics_Mutliformats
for f in $(find *.shp)
do
	ogr2ogr -f "GeoJSON" script/${f//.shp/}.json $f ${f//.shp/}
done

# rename the json files
cd script
rm build/details/*
for f in $(find *.json)
do
	mv $f build/details/${f//EPSG*/}
done
cd build/details/
for f in $(find *.json)
do
	mv $f ${f//epsg*/}
done
cd ../../

# create the result concatenation
rm build/result.json
touch build/tmp.json
echo "[" >> build/tmp.json
for f in $(find build/details/*)
do
	cat $f >> build/tmp.json
	echo "," >> build/tmp.json
done
sed '$s/.$//' build/tmp.json > build/result.json
echo "]" >> build/result.json
rm build/tmp.json

