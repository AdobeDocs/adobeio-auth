# IMS APIs

Here is a list of Adobe Identity Management Services (IMS) which can be useful for specific use cases.

## Logout

Invalidates the user's access token, as well as the refresh token (if any), and the related SSO Renga token (if any). It might or might not redirect the user agent, depending on usage.

Request method | GET or POST
---- | ----
Authorization required | User access token and client ID for front-channel request Client ID and secret for back-channel request
Available versions | `ims-na1.adobelogin.com/ims/logout/v1`  `ims-na1.adobelogin.com/ims/logout/v1/token`

### Request syntax for front-channel logout

Request method | GET
---- | ----
Authorization required | Access token
Available versions | <ENV>/ims/logout/v1

Query Parameters | |      
---- | ----
client_id | Your IMS client ID, assigned on registration.
redirect_uri | A URL to which the user agent is redirected on successful logout.
access_token | Optional, the user's base-64 encoded access token. If specified and still valid, IMS attempts to revoke this token.
callback | Optional, a JavaScript callback function to handle the JSONP response. If not provided, the body of the successful response contains the requested data in JSON format.

#### Example
```
GET /ims/logout/v1?redirect_uri=REDIRECT_URI&access_token=ACCESS_TOKEN&client_id=CLIENT_ID HTTP/1.1
Host: ims-na1.adobelogin.com

```
After internal redirects, IMS redirects the user agent back to the supplied redirect_uri location.

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
Available versions | <ENV>/ims/logout/v1

Query Parameters | |      
---- | ----
client_id | Your IMS client ID, assigned on registration.
client_secret | The IMS client-secret credential, assigned on registration.
redirect_uri | A URL to which the user agent is redirected on successful logout.
access_token | Optional, the user's base-64 encoded access token. If specified and still valid, IMS attempts to revoke this token.
callback | Optional, a JavaScript callback function to handle the JSONP response. If not provided, the body of the successful response contains the requested data in JSON format.

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
Available versions | <ENV>/ims/logout/v1/token

Query Parameters | |
---- | ----
access_token | Optional, the user's base-64 encoded access token. If specified and still valid, IMS attempts to revoke this token.
client_id | 	Mandatory for front-channel CORS requests, optional for front-channel JSONP requests.
callback | Optional, a JavaScript callback function to handle the JSONP response. If not provided, the body of the successful response contains the requested data in JSON format.

#### Example

Request using JSONP
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






