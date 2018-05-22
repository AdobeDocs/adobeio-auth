# OAuth 2.0 Example: Python

This sample app will show you how to implement Adobe OAuth 2.0 in Python using the Flask framework.

After setting up the sample, you will have a Python app that:

1. Serves `templates/index.html` on `https://localhost:8000`
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
    1. [Install Python libraries](#installpythonlibraries)
    1. [Enter your Flask secret and  Adobe API credentials](#enteryourflasksecretandadobeapicredentials)
1. [Usage](#usage)
1. [Other Resources](#otherresources)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## GitHub

You can find a companion repo for this developer guide [on GitHub](https://github.com/adobeio/adobeio-documentation/tree/master/auth/OAuth2.0Endpoints/samples/adobe-auth-python).

Be sure to follow all instructions in the `readme`.


## Technology Used

1. Python 2.6 or greater and the `pip` package manager
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

Adobe OAuth 2.0 requires SSL, so you will need to create a self-signed cert using the OpenSSL CLI:

```
$ openssl req -x509 -newkey rsa:4096 -nodes -out cert.pem -keyout key.pem -days 365
```

Make sure that after running this command you have the `cert.pem` and `key.pem` files at the top level of the sample app.


### Install Python libraries

This sample app uses the [Flask](http://flask.pocoo.org/), [Requests](http://docs.python-requests.org/), and [Six](https://pythonhosted.org/six/) libraries. You can install them using the `pip` package manager:

```
$ pip install flask
$ pip install requests
$ pip install six
```


### Enter your Flask secret and  Adobe API credentials

Enter the required credentials in `config.py`:

```
class Config(object):
    FLASK_SECRET = 'PLACEHOLDER_SECRET_KEY'
    ADOBE_API_KEY = 'YOUR_ADOBE_KEY'
    ADOBE_API_SECRET = 'YOUR_ADOBE_SECRET'
```

You can get your Adobe API Key and Secret from your registered app page on the [Adobe I/O Console](../../web-oauth2.0-guide.md#register-your-application-and-enable-apis).


## Usage

After completing the configuration steps, run `adobe-oauth2.0.py`:

```
$ python adobe-oauth2.0.py
```

To access the app, go to `https://localhost:8000`. Click through any cert warnings in the browser.


## Other Resources

- [Adobe OAuth 2.0 Guide for Web](../../web-oauth2.0-guide.md)
