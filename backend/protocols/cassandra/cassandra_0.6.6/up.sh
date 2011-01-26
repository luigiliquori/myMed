#!/bin/bash

# STARTING CASSANDRA
clear
./bin/cassandra > /dev/null
uptime=$(expr $RANDOM % 50 + 10)
        echo -e "\033[33m 
  _/    _/  _/_/_/    
 _/    _/  _/    _/   
_/    _/  _/    _/    
 _/_/_/  _/_/_/       
        _/            
       _/ 
	   
	   "
