# IMS APIs

Here is a list of Adobe Identity Management Services (IMS) APIs, which can be useful for specific use cases.

* [UserInfo](#userinfo)
* [Token revocation](#token-revocation)
* [Logout](#logout)

## UserInfo

To return information about a specific user, send a GET request to the `/userinfo` endpoint:

`https://ims-na1.adobelogin.com/ims/userinfo/v2`

#### Parameters

|Parameter|Mandatory|Description|
|---|---|---|
|`client_id`|Yes|Your client ID.|

#### Request

The request includes an `Authorization` header with the value `Bearer {ACCESS_TOKEN}`.

```curl
curl -X GET \
  'https://ims-na1.adobelogin.com/ims/userinfo/v1?client_id={YOUR_CLIENT_ID}' \
  -H 'Authorization: Bearer {ACCESS_TOKEN}' \
```

#### Response

```json
{
  "sub": "B0DC108C5CD449CA0A494133@c62f24cc5b5b7e0e0a494004",
  "account_type": "ent",
  "email_verified": "true",
  "address": {
    "country": "US"
  },
  "name": "John Sample",
  "given_name": "John",
  "family_name": "Sample",
  "email": "jsample@email.com"
}
```

|Properties|Projected by scope|Description|
|---|---|---|
|`sub`|`openid`|Unique user ID.|
|`account_type`|`profile`|Can be one of:<br/><ul><li>**`ind`:** User is an individual account.</li><li>**`ent`:** User is part of an organization.</li></ul>|
|`email_verified`|`email`|Specifies if the user has verified their email.|
|`address`|`address`|Address of user. Only the two-digit country code is returned.|
|`name`|`profile`|Full name of user.|
|`given_name`|`profile`|Given name of user.|
|`family_name`|`profile`|Family name or last name of user.|
|`email`|`email`|User email address.|

## Token revocation

To revoke access tokens, send a POST request to the `/revoke` endpoint:

`https://ims-na1.adobelogin.com/ims/revoke`

**Note:** Users can revoke access to your application themselves by visiting the [Connected Applications](https://accounts.adobe.com/security/connected-applications#) page. The next time the user launches your application, the authorization workflow will start from the beginning.

#### Parameters

Parameters can be sent in the body or as query parameters. Passing parameters in the body is recommended for sensitive data, as query parameters may be logged by app servers.

|Parameter|Mandatory|Description|
|---|---|---|
|`client_id`|Only for PUBLIC clients| Your client id.|
|`token`|Yes|Token you are invalidating. Can be an access token or a refresh token.

#### Authorization by client type

|Client Type|Authorization|
|---|---|
|Confidential|Basic Authorization header.<br/><br/>`Authorization: Basic Base64(clientId:clientSecret)`|
|Public|Client Id passed as parameter.|

#### Request for confidential client

```curl
curl -X POST \
  https://ims-na1.adobelogin.com/ims/revoke \
  -H 'Authorization: Basic {AUTHORIZATION}' \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'token={TOKEN}'
```

#### Request for PUBLIC client

```curl
curl -X POST \
  https://ims-na1.adobelogin.com/ims/revoke?client_id={CLIENT_ID} \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'token={TOKEN}'
```
  
#### Response

A successful response returns HTTP Status 200 (OK) and no response body.

## Logout

Invalidates the user's access token, as well as the refresh token (if any), and the related SSO Renga token (if any). It might or might not redirect the user agent, depending on usage.

Request method | GET or POST
---- | ----
Authorization required | User access token and client ID for front-channel request Client ID and secret for back-channel request
Available versions | `ims-na1.adobelogin.com/ims/logout/v1`<br/>`ims-na1.adobelogin.com/ims/logout/v1/token`

### Request syntax for front-channel logout

Request method | GET
---- | ----
Authorization required | Access token
Available versions | /ims/logout/v1

Parameter |Description |      
---- | ----
`client_id` | Your IMS client ID, assigned on registration.
`redirect_uri` | A URL to which the user agent is redirected on successful logout.
`access_token` | Optional, the user's base-64 encoded access token. If specified and still valid, IMS attempts to revoke this token.
`callback` | Optional, a JavaScript callback function to handle the JSONP response. If not provided, the body of the successful response contains the requested data in JSON format.

#### Example

```
GET /ims/logout/v1?redirect_uri=REDIRECT_URI&access_token=ACCESS_TOKEN&client_id=CLIENT_ID HTTP/1.1
Host: ims-na1.adobelogin.com

```
After internal redirects, IMS redirects the user agent back to the supplied `redirect_uri` location.

**Success response**
```
HTTP/1.1 302 Moved Temporarily
Cache-Control: no-store
Location: REDIRECT_URI
```

### Request syntax for back-channel logout with redirect

Request method | GET / POST
---- | ----
Authorization required | Client ID and secret
Available versions | /ims/logout/v1

Parameter | Description|      
---- | ----
`client_id` | Your IMS client ID, assigned on registration.
`client_secret` | The IMS client-secret credential, assigned on registration.
`redirect_uri` | A URL to which the user agent is redirected on successful logout.
`access_token` | Optional, the user's base-64 encoded access token. If specified and still valid, IMS attempts to revoke this token.
`callback` | Optional, a JavaScript callback function to handle the JSONP response. If not provided, the body of the successful response contains the requested data in JSON format.

#### Example

```
GET /ims/logout/v1?client_id=CLIENT_ID&client_secret=CLIENT_SECRET&access_token=ACCESS_TOKEN HTTP/1.1
Host: ims-na1.adobelogin.com
```
**Success response**
```
HTTP/1.1 200
Cache-Control: no-store
```

### Request syntax for non-redirect logout

Request method | GET / POST
---- | ----
Authorization required | Access token
Available versions | /ims/logout/v1/token

Parameters |Description |
---- | ----
`access_token` | Optional, the user's base-64 encoded access token. If specified and still valid, IMS attempts to revoke this token.
`client_id` | 	Mandatory for front-channel CORS requests, optional for front-channel JSONP requests.
`callback` | Optional, a JavaScript callback function to handle the JSONP response. If not provided, the body of the successful response contains the requested data in JSON format.

#### Example

```
GET /ims/logout/v1/token?access_token=ACCESS_TOKEN&callback=callbackName HTTP/1.1
Host: adobeid-na1.services.adobe.com
Cookie: rm=COOKIE; WCDServer=renga*RENGA_TOKEN
```
**Success response**
```
HTTP/1.1 200 OK
Set-Cookie: rm=; Domain=.adobe.com; Expires=Wed, 22-Aug-2012 10:35:00 GMT; Path=/; Secure; HttpOnly
Cache-Control: no-store
P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"
X-RHH: 571465EA68BC81FCFBB6A429B6166882
Set-Cookie: WCDServer=""; Domain=.adobe.com; Expires=Thu, 01-Jan-1970 00:00:10 GMT; Path=/; Secure
Set-Cookie: RengaRMT=""; Domain=.adobe.com; Expires=Thu, 01-Jan-1970 00:00:10 GMT; Path=/; Secure
Content-Type: application/javascript;charset=UTF-8
Transfer-Encoding: chunked
Content-Encoding: gzip
Vary: Accept-Encoding
Date: Wed, 22 Aug 2012 10:35:00 GMT
Server: ASIT
 
callbackName({});
```






