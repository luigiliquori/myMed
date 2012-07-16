#!/usr/bin/python

# Settings
PORT=4201
HOST="localhost"

# Constants
KEYSPACE="Mymed"
CF_MAIL_TEMPLATE="MailTemplates"
USAGE="""
SYNOPSIS:
    install-mail-templates.py [options]
        
        <template>.xml
        template2.xml

        <appID>[#<namepace>]-<lang>.ftl.xml  
        <appID2>[#<namepace2>]-<lang2>.ftl.xml 
        ...

    Install mail templates into cassandra database, 
    for future use by the backend for the notifications.

OPTIONS:
    --host=host : Optional, 'localhost' by default
    --port=port : Optional 4201 by default

FILENAME FORMAT:
    appID     : ID of the application
    namespace : optinal namespace (like a table name)
    lang      : Language : 'fr', 'en', ...

TEMPLATE FORMAT:

    A Template file is an XML file with two elements:  "subject" and "body"
    Each element content may be wrapped into a CDATA section. 
    Each element content is a Freemarker template (http://freemarker.sourceforge.net/).

    The templates can call any of the following variables :
        * base_url : "http://host:port/"
        * application : ApplicationID
        * publisher : The publisher (MUserBean)
        * recipient : The suscriber (MuserBean)
        * publication : The publication, with data and predicates as attributes.
        * predicate : The Predicate, concatened

========= myApp-fr.ftl.xml ==================================
<mail-template>

    <subject>[MyMed/${application}] Notification</subject>

    <body><![CDATA[

        Hello ${recipient.name},<br/>
        <br/>
        
        A new publication is available on the MyMed platform.<br/>
        Application : ${application}<br/>
        <br/>
        
        Publisher : ${publisher.name}<br/>
        Publication :
        <ul>
            <#list publication?keys as key>
                <li><b>${key}</b> : ${publication[key]}</li>
            </#list>
        </ul>
        
        --<br/>
    
        The MyMed team<br/>
]]></body>
</mail-template>
"""

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

# ---------------------------------------------------------------------------
# Utils
# ---------------------------------------------------------------------------

def diewith(msg) :
    print msg
    sys.exit(-1)

# ---------------------------------------------------------------------------
# Main
# ---------------------------------------------------------------------------

# Parse args
parser = OptionParser()
parser.add_option("--host", dest="host", help="Hostname (localhost by default)", default=HOST)
parser.add_option("--port", dest="port", help="Port (4201 by default)", default=PORT)
(options, args) = parser.parse_args()

# No args => usage
if len(args) == 0 :
    diewith(USAGE)

# Get connection to cassandra
# or, we can specify our servers:
pool = pycassa.ConnectionPool(KEYSPACE, server_list=[options.host + ":" + str(options.port)])
cf = pycassa.ColumnFamily(pool, CF_MAIL_TEMPLATE)

# Loop on filenames
for filename in args :

    # Remove dir and extensions
    base = basename(filename)
    while True :
        (base, ext) = splitext(base)
        if ext == '' : break

    
    try:
        (prefix, lang) = base.split('-')
        parts = prefix.split('#')
        appID = parts[0]
        namespace = None
        if len(parts) > 1 :
            namespace = parts[1]
            
    except ValueError, e :
        diewith("File '%s' does not follow format '<appID[#namespace]>-<lang>.ftl.xml'" % filename)

    # Open the file
    doc = ET.parse(filename)

    # Get templates
    subject = doc.find("subject").text
    body = doc.find("body").text

    # Create key <appID> + "#" + 
    key = appID 
    if (namespace != None) :
        key += "#" + namepace
    key += '-' + lang

    # Put the template in the base
    cf.insert(key, {'subject' : subject, 'body': body})

    print "Template correctly set for : app-id=%s, namespace=%s, lang=%s. Key:%s" % (appID, namespace, lang, key)
