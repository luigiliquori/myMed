#! /bin/bash

#
# Transforms Dutch local data from Shape or MapInfo  format to flat GML v2
# (no reprojection as this would generate 3D coordinates)
#
# Author: Just van den Broecke
#


# Transformation
function transform() {
    
    # http://trac.osgeo.org/gdal/wiki/ConfigOptions
    # Otherwise we'll loose the CP1252 encoded chars
    export OGR_FORCE_ASCII=NO

    ogr2ogr -f "GML" $2 $1

    # Dutch source is encoded in Windows LATIN1 :-( (CP1252)
    # need to make UTF-8 encoded GML and change namespace id for gml2
    cat $2 | iconv -f CP1252 -t UTF-8 | sed s/gml:/gml2:/g | sed s/:gml/:gml2/g > temp.gml
    mv temp.gml $2

}

# build the json files
for f in $(find *.TAB)
do
	#transform $f $f
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

