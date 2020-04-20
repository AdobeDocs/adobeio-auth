import configparser
import datetime
import json
import os
import jwt
import requests

config_parser = configparser.ConfigParser()
config_parser.read("config.ini")
config = dict(config_parser["default"])

# Config Data. Populate the payload with the config data from the config.ini
token_exchange_url = config["imsexchange"]
jwt_payload_json = {"exp": datetime.datetime.utcnow() + datetime.timedelta(seconds=90),
                    "iss": config["iss"],
                    "sub": config["technicalaccountid"],
                    config["metascopes"]: True,
                    "aud": "https://{}/c/{}".format(config["imshost"], config["apikey"])}

# Request Access Key
# This Needs to point to where your private key is on the file system. Currently, it points to your user dir.
with open(os.path.join(os.path.expanduser('~'), config["key_file"]), 'rb') as f:
    private_key = f.read()

# Encode the jwt Token. The cryptography module needs to be installed or this will return a token error.
jwt_token = jwt.encode(jwt_payload_json, private_key, algorithm='RS256')

# Get the bearer/access token to use with API requests.
access_token_request_payload = {'client_id': config["apikey"],
                                'client_secret': config["secret"],
                                'jwt_token': jwt_token}
result = requests.post(token_exchange_url, data=access_token_request_payload)
resultjson = json.loads(result.text)

# Print out the access token or wrap this in a method and return the access token.
print("Use this token to make API requests:")
print(resultjson["access_token"])
