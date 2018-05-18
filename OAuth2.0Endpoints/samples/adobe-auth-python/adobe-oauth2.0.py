import os
import flask
import requests
from six.moves import urllib
import json

# Start flask app
app = flask.Flask(__name__)

# Load config object from config.py
app.config.from_object('config.Config')

# Loading FLAST_SECRET from config.py
app.secret_key = app.config['FLASK_SECRET']

@app.route('/')
def home():
  return flask.render_template('index.html')

@app.route('/authorize')
def authorize():
	# Adobe OAuth2.0 authorization url
	authorization_url = 'https://ims-na1.adobelogin.com/ims/authorize?'
	
	# Store required parameters in a dictionary
	params = {
		'client_id' : app.config['ADOBE_API_KEY'],
		'scope' : 'openid,creative_sdk',
		'response_type' : 'code',
		'redirect_uri' : flask.url_for('callback', _external=True)
	}

	# This will prompt users with the approval page if consent has not been given
	# Once permission is provided, users will be redirected to the specified page
	return flask.redirect(authorization_url + urllib.parse.urlencode(params))

@app.route('/callback')
def callback():
	# Retrive the authorization code from callback
	authorization_code = flask.request.args.get('code')

	# Adobe OAuth2.0 token url
	token_url = 'https://ims-na1.adobelogin.com/ims/token'
	
	# Store required parameters in a dictionary
	# And include the authorization code in it
	params = {
		'grant_type' : 'authorization_code',
		'client_id' : app.config['ADOBE_API_KEY'],
		'client_secret' : app.config['ADOBE_API_SECRET'],
		'code' : authorization_code
	}

	# Use requests library to send the POST request
	response = requests.post(token_url,
		params = params,
		headers = {'content-type': 'application/x-www-form-urlencoded'})

	# After receiving a 'OK' response, 
	if response.status_code == 200:
		# save credentials to session
		flask.session['credentials'] = response.json()
		return flask.render_template('index.html', response='login success')
	else:
		return flask.render_template('index.html', response='login failed')

@app.route('/profile')
def profile():
	# Check if credentials exist. If not, ask the user to log in
	if 'credentials' not in flask.session:
		return flask.render_template('index.html', response='Please log in first')
	else:
		# Retrive the access token from the flask session
		access_token = flask.session['credentials']['access_token']

		# Adobe OAuth2.0 profile url
		profile_url = 'https://ims-na1.adobelogin.com/ims/userinfo'
		
		# Store required parameters in a dictionary
		params = {
			'client_id' : app.config['ADOBE_API_KEY']
		}

		# Use requests library to send the GET request
		response = requests.get(profile_url,
			params = params,
			headers = {'Authorization': 'Bearer {}'.format(access_token)})

		if response.status_code == 200:
			return flask.render_template('index.html', response=json.dumps(response.json()))
		else:
			return flask.render_template('index.html', response='profile could not be retrieved')

if __name__ == '__main__':
	# Make sure the hostname and port you provide match the valid redirect URI
	# specified in your project in the Adobe developer Console. 
	# Also, make sure to have `cert.pem` and `key.pem` in your directory
	app.run('localhost', 8000, debug=True, ssl_context=('cert.pem', 'key.pem'))