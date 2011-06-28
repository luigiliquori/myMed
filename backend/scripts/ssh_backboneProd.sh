#!/bin/sh

# ssh_backbone.sh
# 
#
# Created by laurent vanni on 10/14/10.
# Copyright 2010 INRIA All rights reserved.


# ---------------------------------------------------------------> COMMAND LINE
command=$1

# ---------------------------------------------------------------> MYMED
ssh -p 8822 mymed1.sophia.inria.fr		$command
ssh -p 8822 mymed2.sophia.inria.fr		$command	# MAIN NODE
ssh -p 8822 mymed3.sophia.inria.fr		$command
ssh -p 8822 mymed4.sophia.inria.fr		$command
ssh -p 8822 134.59.12.39			$command	# Univ. Nice: Valrose - Salon de Musique
ssh -p 8822 134.59.74.51			$command	# Univ. Nice: St-Jean d'Angely - Mr Gasperini
ssh -p 8822 134.59.12.45			$command	# Univ. Nice: Valrose - Mme Bertone
ssh -p 8822 mymed8.sophia.inria.fr		$command
ssh -p 8822 mymed9.sophia.inria.fr		$command
ssh -p 8822 mymed10.sophia.inria.fr		$command
