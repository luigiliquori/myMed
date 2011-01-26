#!/bin/sh -e
val=$(date +%Y%m%d)
#pathtocassandra=/local/mymed/cassandra_0.6.6
pathtocassandra=/local/mymed/backend/model/protocol/cassandra/cassandra_0.6.6
mv $pathtocassandra/conf/storage-conf.xml $pathtocassandra/conf/scripts/backup/$val-storage-conf.xml
cat $pathtocassandra/conf/scripts/part1 $pathtocassandra/conf/scripts/part2 $pathtocassandra/conf/scripts/part3 > $pathtocassandra/conf/storage-conf.xml
