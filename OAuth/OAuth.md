# OAuth 2.0 Authentication and Authorization

Adobe Cloud Platform APIs use the OAuth 2.0 protocol for authentication and authorization. Using Adobe OAuth 2.0, you can generate an access token using the [OAuth Integration](../AuthenticationOverview/OAuthIntegration.md) created in [Adobe Developer Console](https://www.adobe.com/go/devs_console_ui) which is used to make API calls from your web server or browser-based apps.

* [Basic workflow steps](#basic-workflow-steps)
* [Discovery endpoint](#discovery)
* [Authorization](#authorization)
* [Access tokens](#access-tokens)
    * [Exchange Authorization code](#exchanging-the-authorization-code)
    * [Exchange refresh token](#exchanging-refres-tokens)

## Basic workflow steps

The basic web OAuth 2.0 workflow includes the following steps:

1. Your application _redirects_ the user to Adobe along with the list of requested permissions.
2. Adobe prompts the user with a login screen and informs the user of the requested permissions.
3. The user decides whether to grant the permissions.
4. Adobe sends a _callback_ to your application to notify whether the user granted the permissions.
5. After permissions are granted, your application retrieves tokens required to make API requests on behalf of the user.

The process of providing secure access to protected resources has two stages, _authorization_ and _authentication_. It is important to understand that they are separate concepts:

- **Authorization** is the process of granting permission to a user to access a protected resource. Because authentication is usually a prerequisite for granting access, these two terms often occur together.
- **Authentication** is the process of determining that a user is who they claim to be. Authentication can be checked by Adobe&rsquo;s own identity provider, the Identity Management Services (IMS).

## Discovery

A discovery endpoint is provided to view details of the `openid` configuration: 

[`https://ims-na1.adobelogin.com/ims/.well-known/openid-configuration`](https://ims-na1.adobelogin.com/ims/.well-known/openid-configuration)

The Keys with which ID Tokens are signed can be found here: [`https://ims-na1.adobelogin.com/ims/keys`](https://ims-na1.adobelogin.com/ims/keys)

## Authorization

The first step is to request the authorization token. This request sets the access scope and asks the user to grant permission to your application. To start this workflow, redirect the user to the `authorize` endpoint: 

`https://ims-na1.adobelogin.com/ims/authorize/v2`

**Note:** Ensure that you are using the latest version (`v2`) of the `/authorize` endpoint.

#### Parameters

|Parameter|Mandatory|Description|
|---|---|---|
|`client_id`|Yes|The client ID obtained from [Adobe Developer Console](https://www.adobe.com/go/devs_console_ui/).|
|`redirect_uri`| No|The URI to which the user agent is redirected once the authorization completes. Note that this URI must be HTTPS. The pattern is validated against the list of valid redirect URIs configured for your client. If the redirect URI is not provided with the request or it does not validate against the allowed redirects, it will consider the Default Redirect URI in Adobe Developer Console.|
|`scope`|No|The scope of the access request, expressed as a list of splace or comma delimited, case-sensitive strings. See the [OAuth 2.0 Scopes reference document](Scopes.md) for more information.|
|`response_type`|No|Possible values are `code`, `token`, `id_token`, `id_token token`, `code id_token`. The default response type for the Authorization code flow is `code`.|
|`state`|Recommended|Client-defined state data that is replayed back to the client. It must not be longer than 4096 characters. Does not need to be json. Typically, Cross-Site Request Forgery (CSRF, XSRF) mitigation is done by cryptographically binding the value of this parameter with a browser cookie.|
|`nonce`|No|String value used to associate a Client session with an ID Token and to mitigate replay attacks. The value is passed through unmodified from the Authentication Request to the ID Token.|
|`prompt`|No|Space delimited, case sensitive list of ASCII string values that specifies whether the Authorization Server prompts the End-User for authentication or redirects back if the user is not authenticated. Supported values: `none`, `login`.<br/><ul><li>none → Does not show any UI. Either returns successfully with a valid authentication response or returns with an error.<ul><li>error=login_required → No user is logged in.</li><li>error=consent_required → User is Logged in, but has not consented to your app.</li><li>error=interaction_required → User is logged in, has given consent, but there is some other action they needs to perform (accept terms of use, etc.).</li></ul></li><li>login → Even if the user is authenticated, they will see the login screen.</li></ul>|
|`code_challenge`|Yes, for Public Clients|`code_challenge` value depends on `code_challenge_method` (see next line).|
|`code_challenge_method`|No, default to `plain`|Possible values: `S256`, `plain`<br/><br/><ul><li>For `code_challenge_method` = `plain`<ul><li>`code_challenge` = `code_verifier`</li></ul></li><li>For `code_challenge_method` = `S256`</li><ul><li>`code_challenge` =  `BASE64(SHA256(code_verifier))`</li></ul></li></ul>`code_verifier` is sent on the `/token` endpoint.<br/><br/>For more information, read the [Proof Key for Code Exchange by OAuth Public Clients](https://tools.ietf.org/html/rfc7636) documentation.|
|`response_mode`|No|Possible values: `query`, `fragment`. <br/>For more information, refer to this [openid documentation](https://openid.net/specs/oauth-v2-multiple-response-types-1_0.html#ResponseModes).<br/><br/>If `response_mode` is not specified, the following defaults are applied:<br/><ul><li>code → query</li><li>token → fragment</li><li>id_token → fragment</li><li>id_token token → fragment</li><li>code id_token → fragment</li></ul>|


|Parameter|These are currently included in public-facing doc and not in update wiki.|
|---|---|
|`locale`|The locale code for the authentication UI. Default is `en_US`.|
|`state`|Client-defined state data that is replayed back to the client. It must not be longer than 255 characters. The value should be sent in JSON format: for example, `state={"st":`_`some_alphanumeric_value`_`}`. This parameter should be used to prevent CSRF ([Cross-Site Request Forgery](https://en.wikipedia.org/wiki/Cross-site_request_forgery 'Cross-Site Request Forgery')).|

**Note:** Adobe OAuth does not support the practice of passing base64-encoded `client_id` and `client_secret` parameters using the HTTP BASIC authorization header.

#### Sample request path

The following request path provides an example of several parameters:  

```curl
https://ims-na1.adobelogin.com/ims/authorize/v2?client_id={CLIENT_ID}
&redirect_uri=https://www.myapp.com/OAuth/callback&scope=openid&state={STATE}&response_type=code
```

**Note:** The path has been split onto two lines for readability. A complete request path includes multiple parameters separated by an ampersand (`&`) with no spaces or line breaks.

#### Prompting the user for consent

Once the request from the previous step is sent, Adobe will redirect the user to the Adobe ID sign-in page. After sign-in, the user will see a consent window showing the name of your application and the information that your application is requesting permission to access. The user can allow or deny access by selecting **Allow access** or **Cancel**, respectively.

<kbd>![oauth-5](../Images/oauth-5.png)</kbd> <kbd>![oauth-6](../Images/oauth-6.png)</kbd>

#### Successful response

After the user has authenticated and granted consent to your application, the user agent will be redirected to `{YOUR_REDIRECT_URI}` with the following parameters:

* **`token`:** `access_token={ACCESS_TOKEN}&state={STATE}&token_type=bearer&expires_in=86399`
    * `token_type` will always be `bearer`, `expires_in` is the validity of the token in seconds.  
* **`code`:** `code={AUTHORIZATION_CODE}&state={STATE}`
* **`id_token`:** `id_token={ID_TOKEN}&state={STATE}`
* **`id_token token`:** `id_token={ID_TOKEN}&access_token={ACCESS_TOKEN}&state={STATE}&token_type=bearer&expires_in=86399`
* **`code id_token`:** `id_token={ID_TOKEN}&code={AUTHORIZATION_CODE}&state={STATE}`

They will be in the `query` or the `fragment`, according to the `response_mode` parameter included in the request. If a `response_mode` is not specified, the default values are used as shown in the Authorization parameters table.

## Access tokens

The `/token` endpoint is used for exchanging both the Authorization Code and refresh tokens. These steps are outlined in the following sections:

* [Exchanging the Authorization Code](#exchanging-the-authorization-code)
* [Exchanging refresh tokens](#exchanging-refresh-tokens)

### Exchanging the Authorization Code

After receiving the authorization code, send a POST request to the `/token` endpoint:  

`https://ims-na1.adobelogin.com/ims/token/v3`

#### Parameters

Parameters can be sent in the body or as query parameters. Passing parameters in the body is recommended for sensitive data, as query parameters may be logged by app servers.

|Parameter|Mandatory|Description|
|---|---|---|
|`client_id`|Only for PUBLIC clients|The Client ID obtained from the [Adobe Developer Console](https://www.adobe.com/go/devs_console_ui/).|
|`code`|Yes|The base64-encoded `authorization_code` returned from the `/ims/authorize/` request in callback.
|`grant_type`|Yes|Value should always be `authorization_code`.|
|`code_verifier`|Only for PUBLIC clients|Code verifier generated for the `code_challenge` sent during [Authorization](#authorization).

#### Authorization by client type

|Client Type|Authorization|
|---|---|
|Confidential|Basic Authorization header.<br/><br/>`Authorization: Basic Base64(clientId:clientSecret)`|
|Public|Client Id passed as parameter.|

#### Request for confidential client

```curl
curl -X POST \
  https://ims-na1-stg1.adobelogin.com/ims/token/v3 \
  -H 'Authorization: Basic {AUTHORIZATION}' \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'code={AUTHORIZATION_CODE}&grant_type=authorization_code'
```

#### Request for PUBLIC client

```curl
curl -X POST \
  https://ims-na1-stg1.adobelogin.com/ims/token/v3?client_id={CLIENT_ID} \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'code={AUTHORIZATION_CODE}&grant_type=authorization_code&code_verifier={GENERATED_CODE_VERIFIER}'
```
  
#### Response

```json
{
    "access_token": "{ACCESS_TOKEN}",
    "refresh_token": "{REFRESH_TOKEN}",
    "sub": "B0DC108C5CD449CA0A494133@c62f24cc5b5b7e0e0a494004",
    "id_token": "{ID_TOKEN}",
    "token_type": "bearer",
    "expires_in": 86399
}
```

|Property|Description|
|---|---|
|`access_token`|Generated access token|
|`refresh_token`|Generated refresh token.<br/><br/>`offline_access` scope is needed for this to be returned. See the [OAuth 2.0 Scopes reference document](Scopes.md) for more information.|
|`token_type`|Token type will always be `bearer`.|
|`id_token`|Generated ID token.<br/><br/>Present if `openid` is added as scope. See the [OAuth 2.0 Scopes reference document](Scopes.md) for more information.|
|`expires_in`|Validity of access token in seconds.|

### Exchanging refresh tokens

The default expiry for access tokens is 24 hours. You can refresh an access token without prompting the user for permission again, even if a user is not present. The refresh token, by default, expires in 2 weeks.

This can be done by sending a POST request to the `/token` endpoint:

`https://ims-na1.adobelogin.com/ims/token/v3`

#### Parameters

|Parameter|Mandatory|Description|
|---|---|---|
|`client_id`|Only for PUBLIC clients|The client ID obtained from [Adobe Developer Console](https://www.adobe.com/go/devs_console_ui/integrations).|
|`refresh_token`|Yes|The base64-encoded refresh token received in the response to the initial request for an access token.|
|`grant_type`|Yes|The value is always `refresh_token`.|


**Note:** Not all product APIs support the `refresh_token` grant type. You may not be able to get a valid response for such integrations. Please try creating a **Service Account Integration** for such APIs to create a service-service integration.

#### Authorization by client type

|Client Type|Authorization|
|---|---|
|Confidential|Basic Authorization header.<br/><br/>`Authorization: Basic Base64(clientId:clientSecret)`|
|Public|Client Id passed as parameter.|

#### Request for confidential client

```curl
curl -X POST \
  https://ims-na1-stg1.adobelogin.com/ims/token/v3 \
  -H 'Authorization: Basic {AUTHORIZATION}' \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'grant_type=refresh_token&refresh_token={REFRESH_TOKEN}'
```

#### Request for PUBLIC client

```curl
curl -X POST \
  https://ims-na1-stg1.adobelogin.com/ims/token/v3?client_id={CLIENT_ID} \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'grant_type=refresh_token&refresh_token={REFRESH_TOKEN}'
```
  
#### Response

```json
{
    "access_token": "{ACCESS_TOKEN}",
    "refresh_token": "{REFRESH_TOKEN}",
    "token_type": "bearer",
    "expires_in": 86399
}
```

|Property|Description|
|---|---|
|`access_token`|Generated access token|
|`refresh_token`|Generated refresh token.<br/><br/>`offline_access` scope is needed for this to be returned. See the [OAuth 2.0 Scopes reference document](Scopes.md) for more information.|
|`token_type`|Token type will always be `bearer`.|
|`expires_in`|Validity of access token in seconds.|

## Revoking authorization for end user

Users can revoke access to your application themselves by visiting the [Connected Applications](https://accounts.adobe.com/security/connected-applications#) page. The next time the user launches your application, the authorization workflow will start from the beginning.