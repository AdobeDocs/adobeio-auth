# IMS APIs

Here is a list of Adobe Identity Management Services (IMS) APIs, which can be useful for specific use cases.

* [UserInfo](#userinfo)
* [Token revocation](#token-revocation)

## UserInfo

To return information about a specific user, send a GET request to the `/userinfo` endpoint:

`https://ims-na1.adobelogin.com/ims/userinfo/v2`

#### Parameters

|Parameter|Mandatory|Description|
|---|---|---|
|`client_id`|No|Your client ID.|

#### Request

The request includes an `Authorization` header with the value `Bearer {ACCESS_TOKEN}`.

```curl
curl -X GET \
  'https://ims-na1.adobelogin.com/ims/userinfo/v2?client_id={YOUR_CLIENT_ID}' \
  -H 'Authorization: Bearer {ACCESS_TOKEN}' \
```

#### Response

```json
{
  "sub": "B0DC108C5CD449CA0A494133@c62f24cc5b5b7e0e0a494004",
  "account_type": "ent",
  "email_verified": true,
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
|`email_verified`|`email`|A boolean value which specifies if the user has verified their email.|
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