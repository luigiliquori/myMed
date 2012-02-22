#! /bin/bash

# build the json files
cd /local/mymed/frontend/www/current/system/templates/application/myRivieraAdmin/Couches_SIG_Equipements_Publics_Mutliformats
for f in $(find *.TAB)
do
	ogr2ogr -f "GeoJSON" script/${f//.TAB/}.json $f ${f//.TAB/}
done

# rename the json files
cd script
rm build/details/*
for f in $(find *.json)
do
	mv $f build/details/$f
done

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

