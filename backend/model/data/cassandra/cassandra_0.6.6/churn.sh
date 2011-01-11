#!/bin/bash

# INIT RANDOM
RANDOM=$(date '+%s')

# INIT
clear
uptime=$(expr $RANDOM % 50 + 10)
        echo -e "\033[33m 
  _/    _/  _/_/_/    
 _/    _/  _/    _/   
_/    _/  _/    _/    
 _/_/_/  _/_/_/       
        _/            
       _/ "
sleep $uptime

# STARTING CHURN SCRIPT
while true
do
	# define a random delay
#	downtime=$(expr $RANDOM % 10 + 1)
	uptime=$(expr $RANDOM % 50 + 10)
	
	# KILL THE CASSANDRA NODE
	#pkill java
	#clear
	#echo -e "\033[31m 
#         _/                                         
#    _/_/_/    _/_/    _/      _/      _/  _/_/_/    
# _/    _/  _/    _/  _/      _/      _/  _/    _/   
#_/    _/  _/    _/    _/  _/  _/  _/    _/    _/    
#" _/_/_/    _/_/        _/      _/      _/    _/ "
#	sleep $downtime

	# RESTART THE CASSANDRA NODE
#	./bin/cassandra > /dev/null
	clear
	echo -e "\033[33m 
  _/    _/  _/_/_/    
 _/    _/  _/    _/   
_/    _/  _/    _/    
 _/_/_/  _/_/_/       
        _/            
       _/ "
	sleep $uptime
done

