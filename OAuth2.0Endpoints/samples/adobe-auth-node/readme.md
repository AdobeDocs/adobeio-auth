# OAuth 2.0 Example: Node.js

This sample app will show you how to implement Adobe OAuth 2.0 using Node.js.

After setting up the sample, you will have a Node.js app that:

1. Runs on `https://localhost:8000`
1. Lets a user log in with their Adobe ID
1. Prompts the user to authorize the app with requested scopes
1. Lets the user view their Adobe ID profile information
1. Lets the user log out


<!-- $ doctoc ./readme.md --title "## Contents" --entryprefix 1. --gitlab --maxlevel 3 -->
<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
## Contents

1. [GitHub](#github)
1. [Technology Used](#technologyused)
1. [Prerequisites](#prerequisites)
1. [Configuration](#configuration)
    1. [Create an OpenSSL cert](#createanopensslcert)
    1. [Install Node.js packages](#installnodejspackages)
    1. [Enter your Adobe API credentials](#enteryouradobeapicredentials)
1. [Usage](#usage)
1. [Other Resources](#otherresources)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## GitHub

You can find a companion repo for this developer guide [on GitHub](https://github.com/adobeio/adobeio-documentation/tree/master/auth/OAuth2.0Endpoints/samples/adobe-auth-node).

Be sure to follow all instructions in the `readme`.

## Technology Used

1. Node.js and the `npm` package manager
1. OpenSSL CLI

## Prerequisites

This guide will assume that you have read the [Adobe OAuth 2.0 Guide for Web](../../web-oauth2.0-guide.md).

You must also have [a registered app on the Adobe I/O Console](../../web-oauth2.0-guide.md#register-your-application-and-enable-apis) with the following settings:

1. `Platform`: web
1. `Default redirect URI`: `https://localhost:8000`
1. `Redirect URI Pattern`: `https://localhost:8000`

## Configuration

The following steps will help you get this sample up and running.

### Create an OpenSSL cert

Adobe OAuth 2.0 requires SSL, so you will need to create a self-signed cert using the OpenSSL CLI. Be sure to run this in the `./server` directory:

```
$ cd server
$ openssl req -x509 -newkey rsa:4096 -nodes -out cert.pem -keyout key.pem -days 365
```

Make sure that after running this command you have the `cert.pem` and `key.pem` files at the top level of the `.server` directory.

### Install Node.js packages

The `package.json` file contains a list of dependencies. Run the following command from the top level directory of the app to install these dependencies:

```
$ cd ..
$ npm install
```

### Enter your Adobe API credentials

Enter the required credentials in `public/config.js`:

```javascript
const adobeApiKey = "YOUR_API_KEY";
const adobeApiSecret = "YOUR_API_SECRET";

try {
  if (module) {
    module.exports = {
      adobeApiKey: adobeApiKey,
      adobeApiSecret: adobeApiSecret,
    }
  }
}
catch (err) {}
```

You can get your Adobe API Key and Secret from your registered app page on the [Adobe I/O Console](../../web-oauth2.0-guide.md#register-your-application-and-enable-apis).


## Usage

After completing the configuration steps, start the server:

```
$ npm start
```

To access the app, go to `https://localhost:8000`. Click through any cert warnings in the browser.

## Other Resources

- [Adobe OAuth 2.0 Guide for Web](../../web-oauth2.0-guide.md)
