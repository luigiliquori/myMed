#!/usr/bin/python
import sys
from mymed_backend_test import *

# Settings
BASE_URL="http://localhost:8080/backend"
USER_ID="MYMED_raphael.jolivet@gmail.com"
PASSWORD="tddvdc"
APP="MyTest"
TOKEN='TOTO'

def json_print(obj) :
    print(json.dumps(obj, sort_keys=True, indent=4))

# Init connection to backend
initConnection(BASE_URL)

# ==================== Pulish / Find test ======================================================

# Authenticate
call(SessionRH, CREATE, POST, {
    'userID' : USER_ID, 
    'accessToken' : TOKEN
})

# Get full user description (Why ??)
res = call(ProfileRH, READ, GET, {
    'id' : USER_ID, 
    'accessToken' : TOKEN
})

res=json.loads(res['user'])
json_print(res);

user=json_decode(res['user'])

# Predicate (tranformed into ontology)
pred = keyOnt({'title': 'toto'})

# Publish 
res=call(PublishRH, CREATE, POST, {
    'user'        : user, 
    'accessToken' : TOKEN,
    'application' : APP,
    'predicate'   : pred,
    'data'        : pred + textOnt( # Data = pred + ontology(data)
        {
            'text':'content'})
})

# Update
res=call(PublishRH, CREATE, POST, {
    'user'        : user, # XXX Why do we need the full user data here ??
    'accessToken' : TOKEN,
    'application' : APP,
    'predicate'   : pred,
    'data'        : pred + textOnt( # Data = pred + ontology(data)
        {
            'text2':'other content'})
})


# Find the published content (should contain both "text" & "text2")
res=call(FindRH, READ, GET, {
    'user'        : USER_ID, # XXX Warning, user is actually only the user_ID here
    'accessToken' : TOKEN,
    'application' : APP,
    'predicate'   : inlinePred(pred)
})


# XXX "details" is not recursively JSON and should be deserialized by hand
details = deOnt(json.loads(res['details']))
print "Find Results  : %s" % details

