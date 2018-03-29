# JWT Authentication Quick Start


API services tied to entitled Adobe products (e.g. Campaign, Target, etc.) require a JSON Web Token (JWT) in order to retrieve access tokens for usage against authenticated endpoints. This document serves as a quickstart guide for first-time users.

### Things to check before you start:

You are an organizational admin for your enterprise organization with the appropriate Adobe products entitled. (Check at https://adminconsole.adobe.com/{your_org_id}@AdobeOrg/overview)

### Steps to get a JWT and an access token:

1. Create a new integration in Adobe I/O Console [https://console.adobe.io/integrations](https://console.adobe.io/integrations)

![Create Integration](../img/auth_jwtqs_01.png)

2. Subscribe to an entitled product (e.g. Campaign)

![Subscribe Service](../img/auth_jwtqs_02.png)

3. Create a private key and public certificate. Make sure you store this securely.

`openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:2048 -keyout private.key -out certificate_pub.crt`

4. Upload the public certificate (certificate_pub.crt) as a part of creating the integration.

![Upload Public Certificate](../img/auth_jwtqs_03.png)

5. Your integration should now be created with the appropriate public certificate and claims.

![Integration Created](../img/auth_jwtqs_04.png)

6. Go to the JWT tab and paste in you private key to generate a JWT.

![JWT Tab](../img/auth_jwtqs_05.png)

7. Use the sample curl to get your first access token. 

![Get Access Token](../img/auth_jwtqs_06.png)

The example curl sends a POST request to [https://ims-na1.adobelogin.com/ims/exchange/jwt](https://ims-na1.adobelogin.com/ims/exchange/jwt) with the following parameters.

| Parameter | Description|
|---|---|
| client_id     | The API key generated for your integration. Find this on the I/O Console for your integration.|
| client_secret | The client secret generated for your integration. Find this on the I/O Console for your integration.|
| jwt_token     | A base-64 encoded JSON Web Token that encapsulates the identity of your integration, signed with a private key that corresponds to a public key certificate attached to the integration. Generate this on the I/O Console in the JWT Tab for your integration.|

You now have your access token. Look up documentation for the specific API service you’re hitting authenticated endpoints for to find what other parameters are expected. Most of them need an x-api-key which will be the same as your client id.
