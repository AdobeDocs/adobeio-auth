# OAuth 2.0 Guide for Web

Adobe Cloud Platform APIs use the OAuth 2.0 protocol for authentication and authorization. Using Adobe OAuth 2.0, you can generate an access token which is used to make API calls from your web server or browser-based apps.

The basic Web OAuth 2.0 workflow will look like:

1. Your application redirects the user to Adobe along with the list of requested permissions
1. Adobe prompts the user with a login screen and informs the user of the requested permissions
1. The user decides whether to grant the permissions
1. Adobe sends a callback to your application to notify whether the user granted the permissions
1. After permissions are granted, your application retrieves tokens required to make API requests on behalf of the user

By the end of this guide, you will be able to:

1. Generate authorization codes
1. Have Adobe prompt users for consent
1. Handle the OAuth 2.0 server callback
1. Generate access tokens
1. Use refresh tokens to generate access tokens
1. Log out users
1. Call Adobe APIs

<!-- doctoc command config: -->
<!-- $ doctoc ./readme.md --title "## Contents" --entryprefix 1. --gitlab --maxlevel 3 -->

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
## Contents

1. [Prerequisites](#prerequisites)
    1. [Register your application and enable APIs](#registeryourapplicationandenableapis)
    1. [Retrieve application credentials](#retrieveapplicationcredentials)
1. [Convenience libraries](#conveniencelibraries)
1. [OAuth endpoints](#oauthendpoints)
    1. [Generating authorization tokens](#generatingauthorizationtokens)
    1. [Prompting the user for consent](#promptingtheuserforconsent)
    1. [Handling the callback](#handlingthecallback)
    1. [Generating access tokens](#generatingaccesstokens)
    1. [Exchanging a refresh token for an access token](#exchangingarefreshtokenforanaccesstoken)
    1. [Revoking authorization for end user](#revokingauthorizationforenduser)
1. [Complete examples for OAuth endpoints](#completeexamplesforoauthendpoints)
    1. [Node.js Example](#nodejsexample)
    1. [Python Example](#pythonexample)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Prerequisites

### Register your application and enable APIs

Your application needs to be registered with Adobe and a specific set of Adobe APIs need to be enabled to start making API calls. To enable these APIs:

1. [Go to the integration page in the Adobe I/O Console](https://console.adobe.io/integrations).

    - If you do not already have an Adobe ID, you will need to create one.

1. Create a new integration and select the APIs you want to enable.
1. Provide required information:

	- `Name`: Your application’s name. This will not be sent in your API requests.
	- `Description`: A simple description of your application.
	- `Platform`: Choose the platform your application runs on.
	- `Default redirect URI`: Choose a secure URL to receive callbacks from Adobe during the authentication process. This is the redirect URL that will be used if the requested `redirect_uri` does not match the redirect URI pattern or is not provided. It must be hosted on a secure (HTTPS) server, even if it is a localhost instance. For an example, check one of the [`samples`](samples).
	- `Redirect URI Pattern`: This is a URI path (or comma-separated list of paths) to which Adobe will attempt to redirect when the login flow is complete. To emphasize, this is a list of the **allowed** callback URL patterns, not the ones Adobe will attempt. It must be within your application domain, and all be on a secure (HTTPS) server. You must escape periods `.` with a backslash `\` (e.g. `https://mysite\.com/`).


### Retrieve application credentials

After registering your application, [access the integration details page on the Adobe I/O Console](https://console.adobe.io/integrations) to retrieve the Client ID (API Key) and Client Secret values:

![Credentials page on the Adobe I/O Console](assets/credentials_page.png?raw=true)


## Convenience libraries

In addition to providing OAuth endpoints, Adobe provides OAuth libraries for some popular environments.

- Node.js: [`passport-adobe-oauth2` Passport strategy on npm](https://www.npmjs.com/package/passport-adobe-oauth2)


## OAuth endpoints

### Generating authorization tokens

The first step is to request the authorization token. This request sets the access scope and asks the user to grant permission to your application.

In order to start this workflow, redirect the user to the Adobe's authorization endpoint:

```
https://ims-na1.adobelogin.com/ims/authorize
```

Include the following parameters:

| Parameters    | Description                                             |
| ------------- |:--------------------------------------------------------|
| client_id     | The Client ID obtained from the [Adobe I/O Console](https://console.adobe.io/integrations).|
| redirect_uri  | The URI to which the user agent is redirected once the authorization completes. Note that this URI must be HTTPS. The pattern is validated against the list of valid redirect URIs configured for your client.|
| scope         | The scope of the access request, expressed as a list of comma-delimited, case sensitive strings. See the [Scope Reference](oauth-scopes.md) for more information.|
| response_type | The default `response_type` for the Authorization code flow is `code`. Use `token` for the Implicit grant flow. See below for more information. |
| locale | Optional. The locale code for the authentication UI. Default is en_US.|
| state | Optional. Client-defined state data that is replayed back to the client. It must not be longer than 255 characters. The value should be sent in JSON format, for example `state={“st”:some_alphanumeric_value}`. This parameter should be used to prevent [CSRF](https://tools.ietf.org/html/rfc6749#section-10.12) (Cross-Site Request Forgery).|

_Note that Adobe OAuth does not support the practice of passing Base64 encoded `client_id` and `client_secret` using the HTTP BASIC authorization header._

#### Authorization code flow (`response_type = code`)

The `code` response type used for "Authorization code flow" is often used by web server apps that are written in a server-side language and run on a server where the source code is not seen by the public. Thus, the application is able to use the client secret when communicating with the auth server. See below for the "Authorization code flow" steps:

1. Client initiates the flow by directing the resource owner's user-agent to the authorization endpoint. The request includes your client identifier, requested scope, optional local state information, and a redirect URI that the authorization server can use to send back the authorization code when authentication succeeds and authorization is granted.
1. The authorization server authenticates the resource owner through the user agent, which is typically a web browser.
1. If authentication is successful, the authorization server redirects the user-agent back to the client, using the redirect URI provided in the request. The response includes an authorization code, and any local state information that you sent with the request.
1. Your client requests an access token from the token endpoint. The client must authenticate itself by including its own client credentials in this request, as well as the authorization code received in the response to the initial request.
1. The authorization server validates both the client credentials and the authorization code. If valid, it responds with both an access token and a refresh token.
1. When the access token expires, you can use the refresh token to request a new access token.

For more information, please refer to the [OAuth 2.0 specification](https://tools.ietf.org/html/rfc6749#section-4.1).

Example URL (`code` response type):

```
https://ims-na1.adobelogin.com/ims/authorize
?client_id=<client_id>
&redirect_uri=https://oauth2-adobe.example.com/auth
&scope=openid,creative_sdk
&response_type=code
```
_Note: Refresh token and access token must be secured during transit and storage. Make sure to use TLS and to encrypt tokens while storing them at rest._

#### Implicit grant flow (`response_type = token`)

On the other hand, the `token` response type used for "Implicit grant flow" is often used by SPAs (Single-Page Apps) or browser-based apps that run entirely in the browser. Since the source code is available to the browser, there's no way to hide the client secret.  Thus, your app will receive the `access_token` right after the user provides consent. However, note that the `refresh_token` will not be provided for this flow. See below for the "Implicit grant flow" steps:

1. Client initiates the flow by directing the resource owner's user-agent to the authorization endpoint. The client includes its client identifier, requested scope, state, and a redirect URI to which Adobe Auth will send the user-agent back, once access is granted.
1. The authorization server authenticates the resource owner via the user-agent.
1. Assuming the resource owner authenticates, the authorization server redirects the user-agent back to the client using the redirect URI provided earlier. The redirection URI includes the access token in the URI fragment.
	1. The user-agent follows the redirection instructions by making a request to the web-hosted client resource (which does not include the fragment). The user-agent retains the fragment information locally.
1. The web-hosted client resource returns a web page (typically an HTML document with an embedded script) capable of accessing the full redirection URI including the fragment retained by the user-agent, and extracting the access token and other parameters contained in the fragment.
1. The user-agent executes the script provided by the web-hosted client resource locally, which extracts the access token (no refresh token provided). 

For more information, please refer to the [OAuth 2.0 specification](https://tools.ietf.org/html/rfc6749#section-4.2). 

Example URL (`token` response type):

```
https://ims-na1.adobelogin.com/ims/authorize
?client_id=<client_id>
&redirect_uri=https://oauth2-adobe.example.com/auth
&scope=openid,creative_sdk
&response_type=token
```
_Note: Follow security best practices while storing the access token on the user’s browser. Make sure to use the browser’s session storage or cookies with the “secure” flag enabled._

### Prompting the user for consent

Once the request from the previous step is sent, Adobe will redirect the user to the Adobe ID sign-in page. After sign-in, the user will see a consent window showing the name of your application and the information that your application is requesting permission to access with the user's credentials:

![Adobe ID sign-in](assets/login_screen.png?raw=true) ![Consent page](assets/access_grant.png?raw=true)

The user can either allow or refuse access.


### Handling the callback

Adobe's OAuth 2.0 server will respond to your application's access request by using the redirect URI specified in the request. If the user has signed-in successfully and granted permissions, the OAuth 2.0 server will respond with an authorization code in the query string. If the user has not approved the request, the OAuth 2.0 server will send an error message.

Example Authorization grant type response (`code` response type):

```
https://oauth2-adobe.example.com/auth?code=eyJ4NXUiOiJpbXNfbmExLWtleS....
```

Example Implicit grant type response (`token` response type):

```
https://oauth2-adobe.example.com/auth#access_token=eyJ4NXUiOiJ...&token_type=bearer&expires_in=86399991
```

Example Authorization grant type error response (`code` response type):

```
https://oauth2-adobe.example.com/auth?error=access_denied
```

Example Implicit grant type error response (`token` response type):

```
https://oauth2-adobe.example.com/auth#error=access_denied
```

Note that, if you are using the "Implicit" grant type, you now have an `access_token` and your workflow ends here.

### Generating access tokens
After receiving the authorization code, send a POST request to the token endpoint:

```
https://ims-na1.adobelogin.com/ims/token/
```

Include the following parameters:

| Parameters    | Description                                             |
| ------------- |:--------------------------------------------------------|
| code          |The base-64 encoded `authorization_code` returned from the `/ims/authorize/` request.|
| grant_type    |The constant value `authorization_code`.|
| client_id     |The Client ID obtained from the [Adobe I/O Console](https://console.adobe.io/integrations).|
| client_secret |The Client Secret obtained from the [Adobe I/O Console](https://console.adobe.io/integrations).|

_Note that Adobe OAuth does not support the practice of passing Base64 encoded `client_id` and `client_secret` using the HTTP BASIC authorization header._

Example request:

```
POST /ims/token HTTP/1.1
Host: ims-na1.adobelogin.com

Content-Type: application/x-www-form-urlencoded
grant_type=authorization_code
&client_id=<client_id>
&client_secret=<client_secret>
&code=<authorization_code>
```

Example response:

```
HTTP/1.1 200 OK
Content-Type: application/json;charset=UTF-8

{
	"access_token": "eyJ4NXU...",
	"refresh_token": "eyJ4NXU...",
	"sub": "5BEB2BB...@AdobeID",
	"address": {
		"country": "US"
	},
	"email_verified": "true",
	"name": "USERNAME",
	"token_type": "bearer",
	"given_name": "USERNAME",
	"expires_in": 86399985,
	"family_name": "USERNAME",
	"email": "USERNAME@example.com"
}
```

Note that in addition to the `access_token` and `refresh_token`, the response also includes a JSON array of profile data that your `client_id` is authorized for and appropriate for the `scope` that you requested.

### Exchanging a refresh token for an access token

The default expiry of access tokens is 24 hours. You can refresh an access token without prompting the user for permission again even if user is not present. The refresh token, by default, expires in 2 weeks.

You can send a POST request to the token endpoint:

```
https://ims-na1.adobelogin.com/ims/token/
```

Include the following parameters:

| Parameters    | Description                                             |
| ------------- |:--------------------------------------------------------|
| refresh_token |The base-64 encoded `refresh_token` received in the response to the initial request for an access token.|
| grant_type    |The constant value `refresh_token`.|
| client_id     |The Client ID obtained from the [Adobe I/O Console](https://console.adobe.io/integrations).|
| client_secret |The Client Secret obtained from the [Adobe I/O Console](https://console.adobe.io/integrations).|

_Note that Adobe OAuth does not support the practice of passing Base64 encoded `client_id` and `client_secret` using the HTTP BASIC authorization header._

Example request:

```
POST /ims/token HTTP/1.1
Host: ims-na1.adobelogin.com

Content-Type: application/x-www-form-urlencoded
grant_type=refresh_token
&client_id=<client_id>
&client_secret=<client_secret>
&refresh_token=<refresh_token>
```

Example response:

```
HTTP/1.1 200 OK
Content-Type: application/json;charset=UTF-8

{
	"access_token": "eyJ4NXU...",
	"refresh_token": "eyJ4NXU...",
	"sub": "5BEB2BB...@AdobeID",
	"address": {
		"country": "US"
	},
	"email_verified": "true",
	"name": "USERNAME",
	"token_type": "bearer",
	"given_name": "USERNAME",
	"expires_in": 86399985,
	"family_name": "USERNAME",
	"email": "USERNAME@example.com"
}
```

Similar to above, note that in addition to the `access_token` and `refresh_token`, the response also includes a JSON array of profile data that your `client_id` is authorized for and appropriate for the `scope` that you requested.

### Revoking authorization for end user

Users can revoke access to your application themselves by visiting [Connected Applications Page](https://accounts.adobe.com/security/connected-applications#). When the user launches your application next time, the authorization workflow will start from the beginning.

## Complete examples for OAuth endpoints

The following samples demonstrate basic interaction with the Adobe OAuth endpoints.


### Node.js Example

[Github repo](https://github.com/adobeio/adobeio-documentation/tree/master/auth/OAuth2.0Endpoints/samples/adobe-auth-node) where you can find a complete Node.js based web app example that uses Adobe OAuth.


### Python Example

[Github repo](https://github.com/adobeio/adobeio-documentation/tree/master/auth/OAuth2.0Endpoints/samples/adobe-auth-python) where you can find a complete Python based web app example that uses Adobe OAuth.
