# OAuth 2.0 Example: Node.js

This sample app will show you how to implement Adobe OAuth 2.0 using Node.js.

After setting up the sample, you will have a Node.js app that:

1. Runs on `https://localhost:8000`
2. Lets a user log in with their Adobe ID
3. Prompts the user to authorize the app with requested scopes
4. Lets the user view their Adobe ID profile information
5. Lets the user log out


<!-- $ doctoc ./readme.md --title "## Contents" --entryprefix 1. --gitlab --maxlevel 3 -->
<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
## Contents

- [OAuth 2.0 Example: Node.js](#oauth-20-example-nodejs)
  - [Contents](#contents)
  - [Technology Used](#technology-used)
  - [Prerequisites](#prerequisites)
  - [Configuration](#configuration)
    - [Install Node.js packages](#install-nodejs-packages)
    - [Store your Adobe API credentials as Environment Variables](#store-your-adobe-api-credentials-as-environment-variables)
    - [Create an OpenSSL cert for Local Development](#create-an-openssl-cert-for-local-development)
  - [Usage](#usage)
  - [Other Resources](#other-resources)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Technology Used

1. Node.js and the `npm` package manager
1. `mkcert` SSL certificate installer/configuration

## Prerequisites

This guide will assume that you have read the [Adobe OAuth 2.0 Guide for Web](../../OAuth.md).

You must also have [a registered app on the Adobe Developer Console](../../../AuthenticationOverview/OAuthIntegration.md) with the following settings:

1. `Platform`: web
1. `Default redirect URI`: `https://localhost:8000/callback/`
1. `Redirect URI Pattern`: `https://localhost:8000/callback/`

## Configuration

The following steps will help you get this sample up and running.

### Install Node.js packages

The `package.json` file contains a list of dependencies. Run the following command from the top level directory of the app to install these dependencies:

```
$ npm install
```

### Store your Adobe API credentials as Environment Variables

Set up a `.env` file to store the API Key and Secret. Create it on the same level as the `package.json` file -- you should see that it is already included in the `.gitignore` file to ensure that our source control history won't contain references to your secrets.   

```
API_KEY=######################
API_SECRET=###################
```

To use your secrets, just insert this line in your code. 
```
require('dotenv').config();
```
This will require the `dotenv` package and execute the `config` function, which reads the `.env` file and sets your environment variables, which you can now access using `process.env.API_KEY` and `process.env.API_SECRET`.

You can get your Adobe API Key and Secret from your registered app page on the [Adobe Developer Console](../../web-oauth2.0-guide.md#register-your-application-and-enable-apis).


### Create an OpenSSL cert for Local Development

Adobe OAuth 2.0 requires SSL, so you will need to create a self-signed cert using the OpenSSL CLI. On production servers this is not an issue, but users running local development servers will have to do this next step. 

Make sure you have command-line tool `mkcert` installed on your machine first (for Mac users: recommend using `brew install`).

Once it is installed, in the same folder as your entrypoint file (`index.js` in our case), run the line `mkcert localhost`. This will generate key and certificate files. Now we can use the key and certificate to spin up a HTTPS server like so: 

```
/* Set up a HTTPS server with the signed certification */
var httpsServer = https.createServer({
	key: fs.readFileSync(path.join(__dirname,'./localhost-key.pem')),
	cert: fs.readFileSync(path.join(__dirname, './localhost.pem'))
}, app).listen(port, hostname, (err) => {
	if (err) console.log(`Error: ${err}`);
	console.log(`listening on port ${port}!`);
});
```

## Usage

After completing the configuration steps, start the server:

```
$ npm start
```

To access the app, go to `https://localhost:8000`. Try logging in with your Adobe ID first then click "Get Profile" to view relevant user info. You can also log out and the session will destroy user credentials. 

## Other Resources

- [Adobe OAuth 2.0 Guide for Web](../../web-oauth2.0-guide.md)
