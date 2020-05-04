import datetime
import json
import jwt
import os
import requests

# Config Data
url = 'https://ims-na1.adobelogin.com/ims/exchange/jwt'
jwtPayloadRaw = """{ "iss": "{The issuer, your Organization ID from the Adobe Developer Console integration, in the format org_ident@AdobeOrg}",
                     "sub": "{The subject, your Technical Account ID from the Adobe Developer Console integration, in the format: id@techacct.adobe.com}",
                     "{The API-access claim configured for your organization: https://ims-na1.adobelogin.com/s/ent_analytics_bulk_ingest_sdk}": true,
                     "aud": "{The audience for the token, your API Key from the Adobe Developer Console integration, in the format: https://ims-na1.adobelogin.com/c/api_key}" }"""
jwtPayloadJson = json.loads(jwtPayloadRaw)
jwtPayloadJson["exp"] = datetime.datetime.utcnow() + datetime.timedelta(seconds=30)

accessTokenRequestPayload = {'client_id': '{Your Client Id (API Key)}'
                            ,'client_secret': 'Your Client Secret'}

# Request Access Key 
#This Needs to point at where your private key is on the file system
keyfile = open(os.path.join(os.path.expanduser('~'),'.ssh/private.key'),'r') 
private_key = keyfile.read()

# Encode the jwt Token
jwttoken = jwt.encode(jwtPayloadJson, private_key, algorithm='RS256')
#print("Encoded JWT Token")
#print(jwttoken.decode('utf-8'))


# We are making a http request simmilar to this curl request
#curl -X POST -H "Content-Type: multipart/form-data" -F "client_id=6e806c8aa87b42a49260d7a47a8d3218" -F "client_secret=f4813774-c72f-42ca-8039-3208ff189932" -F "jwt_token=`./jwtenc.sh`" https://ims-na1.adobelogin.com/ims/exchange/jwt
accessTokenRequestPayload['jwt_token'] = jwttoken
result = requests.post(url, data = accessTokenRequestPayload)
resultjson = json.loads(result.text);
#print("Full output from the access token request")
#print(json.dumps(resultjson, indent=4, sort_keys=True))

# Echo out the access token
print(resultjson["access_token"]);

