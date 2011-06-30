#!/bin/sh

# scp_backbone.sh
# 
#
# Created by laurent vanni on 10/14/10.
# Copyright 2010 INRIA All rights reserved.

source=$1
destination=$2

# -----------------------------------------------------------------------------> MYMED
scp -P 8822 $source mymed1.sophia.inria.fr:$destination		
scp -P 8822 $source mymed2.sophia.inria.fr:$destination		# MAIN NODE
scp -P 8822 $source mymed3.sophia.inria.fr:$destination		
scp -P 8822 $source mymed4.sophia.inria.fr:$destination	
scp -P 8822 $source 134.59.12.39:$destination				# Univ. Nice: Valrose - Salon de Musique
scp -P 8822 $source 134.59.74.51:$destination				# Univ. Nice: St-Jean d'Angely - Mr Gasperini
scp -P 8822 $source 134.59.12.45:$destination				# Univ. Nice: Valrose - Mme Bertone
scp -P 8822 $source mymed8.sophia.inria.fr:$destination		
scp -P 8822 $source mymed9.sophia.inria.fr:$destination		
scp -P 8822 $source mymed10.sophia.inria.fr:$destination		
