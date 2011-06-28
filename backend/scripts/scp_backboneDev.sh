#!/bin/sh

# scp_backbone.sh
# 
#
# Created by laurent vanni on 10/14/10.
# Copyright 2010 INRIA All rights reserved.

source=$1
destination=$2

# -----------------------------------------------------------------------------> TESTING
scp -P 8822 $source mymed11.sophia.inria.fr:$destination	
scp -P 8822 $source mymed12.sophia.inria.fr:$destination		
scp -P 8822 $source mymed13.sophia.inria.fr:$destination		
scp -P 8822 $source mymed14.sophia.inria.fr:$destination		
scp -P 8822 $source mymed15.sophia.inria.fr:$destination		
scp -P 8822 $source mymed16.sophia.inria.fr:$destination		
scp -P 8822 $source mymed17.sophia.inria.fr:$destination		
scp -P 8822 $source mymed18.sophia.inria.fr:$destination		
scp -P 8822 $source mymed19.sophia.inria.fr:$destination		
scp -P 8822 $source mymed20.sophia.inria.fr:$destination		
scp -P 8822 $source mymed21.sophia.inria.fr:$destination
scp -P 8822 $source mymed22.sophia.inria.fr:$destination		
scp -P 8822 $source mymed23.sophia.inria.fr:$destination		
scp -P 8822 $source mymed24.sophia.inria.fr:$destination		
scp -P 8822 $source mymed25.sophia.inria.fr:$destination		
