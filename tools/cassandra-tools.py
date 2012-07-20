#!/usr/bin/python

# Settings
PORT=4201
HOST="localhost"

# Constants
KEYSPACE="Mymed"


# ---------------------------------------------------------------------------
# Imports
# ---------------------------------------------------------------------------
import sys
from os.path import basename, splitext
import xml.etree.ElementTree as ET
from optparse import OptionParser
try:
    import pycassa
except ImportError, e:
    print "Missing package 'pycassa'. Please run :\nsudo easy_install pycassa"
    sys.exit(-1)


cfName = sys.argv[1]

pool = pycassa.ConnectionPool(KEYSPACE, server_list=[HOST + ":" + str(PORT)])
cf = pycassa.ColumnFamily(pool, cfName)
cf.remove(sys.argv[2])

"""
for row in cf.get_range(sys.argv[2]) :
    print "KEY:" + row[0]
    for key,val in row[1].iteritems() :
        print "   %s : %s" % (key, val)
        """
