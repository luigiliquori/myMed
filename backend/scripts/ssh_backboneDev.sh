#!/bin/sh

# ssh_backbone.sh
# 
#
# Created by laurent vanni on 10/14/10.
# Copyright 2010 INRIA All rights reserved.


# ---------------------------------------------------------------> COMMAND LINE
command=$1

# ---------------------------------------------------------------> TESTING
ssh -p 8822 mymed11.sophia.inria.fr		$command
ssh -p 8822 mymed12.sophia.inria.fr		$command
ssh -p 8822 mymed13.sophia.inria.fr		$command
ssh -p 8822 mymed14.sophia.inria.fr		$command
ssh -p 8822 mymed15.sophia.inria.fr		$command
ssh -p 8822 mymed16.sophia.inria.fr		$command
ssh -p 8822 mymed17.sophia.inria.fr		$command
ssh -p 8822 mymed18.sophia.inria.fr		$command
ssh -p 8822 mymed19.sophia.inria.fr		$command
ssh -p 8822 mymed20.sophia.inria.fr		$command
ssh -p 8822 mymed21.sophia.inria.fr		$command
ssh -p 8822 mymed22.sophia.inria.fr		$command
ssh -p 8822 mymed23.sophia.inria.fr		$command
ssh -p 8822 mymed24.sophia.inria.fr		$command
ssh -p 8822 mymed25.sophia.inria.fr		$command
