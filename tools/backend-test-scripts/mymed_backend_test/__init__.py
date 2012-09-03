#!/usr/bin/python
#
# Some handy utils to test the backend directly
#


import urllib
import json
from restful_lib import Connection

# Requests codes (CRUD)
CREATE=0
READ=1
UPDATE=2
DELETE=3

# Ontology ID (predicate)
KEYWORD=0
GPS=1
ENUM=2
DATE=3

# OntologyID (others) 
TEXT=4
PICTURE=5
VIDEO=6
AUDIO=7

# HTTP method
GET="GET"
POST="POST"

# Request Handlers
AuthenticationRH="AuthenticationRequestHandler"
SessionRH="SessionRequestHandler"
ProfileRH="ProfileRequestHandler"
FindRH="FindRequestHandler"
PublishRH="PublishRequestHandler"
SubscribeRH="SubscribeRequestHandler"


# ---------------------------- Utils ------------------------------

# Init global connection before sending commands
def initConnection(url) :
    global gConn
    gConn = Connection(url)
    
# Transform a dictionnary into ontologies {'key1'='value1'} => {key='key1', value='value1', ontologyID=ontologyId}
def ontology(map, ontologyID) :
    res=[]
    for key, val in map.items() :
        res.append({'key' : key, 'value':val, 'ontologyID':ontologyID})
    return res

# Transform a map to list of ontologies (using KEYWORD ontology id for all)
def keyOnt(map) :
    return ontology(map, KEYWORD)
# Transform a map to list of ontologies (using TEXT ontology id for all)
def textOnt(map) :
    return ontology(map, TEXT)

# Transform ontology list back to simple map
def deOnt(ont) :
    res = {}
    for item in ont : 
        res[item["key"]]=item["value"]
    return res

# Call backend method
def call(handler, request, method, args) :

    # Headers 
    headers={'content-type' : 'application/x-www-form-urlencoded'}

    # Add the request code to the args
    args['code'] = request

    # Encode complex types in JSON
    json_args={}
    for key, value in args.items() :
        # Map or list ?
        if isinstance(value, dict) or isinstance(value, list) :
            json_args[key] = json.dumps(value)
        else:
            json_args[key] = value

    # Switch on method
    if method == GET :
        res=gConn.get(handler, json_args, headers=headers)
    elif method == POST :
        res=gConn.post(handler, json_args, headers=headers)
    
    status = res['headers']['status']
    body = json.loads(res['body'])
    
    # Error ?
    if status != '200' :
        desc = body['description']
        # Trace
        print("Handler:%s\nRequest:%s\nMethod:%s\nArgs:%s" % (handler, request, method, args))
        raise Exception('Service error. Status:%s, Message:%s' % (status, desc)) 

    return body['dataObject']

# Transform to inline predicate {'key':'toto', 'value':'titi', 'ontology':0} => "tototiti"
def inlinePred(predOnt) :
    res=""
    for item in predOnt :
        key = item['key']
        val = item['value']
    res += ""  + key + val
    return res
