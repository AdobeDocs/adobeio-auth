# JWT Authentication Quick Start


API services tied to entitled Adobe products (such as Campaign, Target, Launch, and others) require a JSON Web Token (JWT) to retrieve access tokens for usage against authenticated endpoints. This document serves as a quickstart guide for first-time users.

## Things to check before you start:

You must be an organizational admin for your enterprise organization with the appropriate Adobe products entitled. (Check at https://adminconsole.adobe.com/{your\_org\_id}@AdobeOrg/overview)

## Steps to get a JWT and an access token

Regardless of your platform, you begin with the same steps in Adobe I/O Console:

1. Create a new integration in Adobe I/O Console: [https://console.adobe.io/integrations](https://console.adobe.io/integrations)
    ![Create integration](../img/auth_jwtqs_01.png "Create an integration") 

2. Choose to access an API.

3. Subscribe to an entitled product (for instance, Launch, by Adobe).
    ![Subscribe service](../img/auth_jwtqs_02.png "Subscribe to a product or service")

4. Confirm that you want to create a new integration.

5. Create a private key and a public certificate. Make sure you store these securely.

Once you complete the above steps, your path diverges depending on your platform: 

_**MacOS and Linux:**_

Open a terminal and execute the following command:  

`openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:2048 -keyout private.key -out certificate_pub.crt`

![Generate public certificate](../img/auth_jwtqs_00.png "Generate Public certificate")

_**Windows:**_

1. Download an OpenSSL client to generate public certificates; for example, you can try the [OpenSSL Windows client](https://bintray.com/vszakats/generic/download_file?file_path=openssl-1.1.1-win64-mingw.zip).

2. Extract the folder and copy it to the C:/libs/ location.

3. Open a command line window and execute the following commands:

    `set OPENSSL_CONF=C:/libs/openssl-1.1.1-win64-mingw/openssl.cnf`

    `cd C:/libs/openssl-1.1.1-win64-mingw/`

    `openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:2048 -keyout private.key -out certificate_pub.crt`

    ![Generate public certificate windows](../img/auth_jwtqs_000.png "Generate Public certificate windows")

Once you&rsquo;ve complete the steps for your chosen platform, continue in the Adobe I/O Console:

6. Upload the public certificate (certificate_pub.crt) as a part of creating the integration.
    ![Upload public certificate](../img/auth_jwtqs_03.png "Upload public certificate")

7. Your integration should now be created with the appropriate public certificate and claims.
    ![Integration created](../img/auth_jwtqs_04.png "Integration created")

8. Go to the JWT tab and paste in you private key to generate a JWT.
    ![JWT tab](../img/auth_jwtqs_05.png "JWT tab")

9. Copy the &ldquo;Sample CURL Command&rdquo; to get your first access token. 
    ![Get access token](../img/auth_jwtqs_06.png "Get access token")

10. Open Postman, then click Import &gt; Paste Raw Text and paste the copied curl command.
    ![Postman import](../img/auth_jwtqs_07.png "Postman import")

11. Click Send.
    ![Postman send](../img/auth_jwtqs_08.png "Postman send")

The example curl sends a POST request to [https://ims-na1.adobelogin.com/ims/exchange/jwt](https://ims-na1.adobelogin.com/ims/exchange/jwt) with the following parameters.

| Parameter | Description|
|---|---|
| `client_id` | The API key generated for your integration. Find this on the I/O Console for your integration. |
| `client_secret` | The client secret generated for your integration. Find this on the I/O Console for your integration. |
| `jwt_token` | A base-64 encoded JSON Web Token that encapsulates the identity of your integration, signed with a private key that corresponds to a public key certificate attached to the integration. Generate this on the I/O Console in the JWT Tab for your integration. Note that this token has the expiration time parameter `exp` set to 24 hours from when the token is generated. | 

You now have your access token. Look up documentation for the specific API service for which you’re hitting authenticated endpoints to find what other parameters are expected. Most of them need an `x-api-key`, which will be the same as your `client_id`.
