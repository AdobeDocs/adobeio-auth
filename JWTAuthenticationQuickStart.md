# JWT Authentication Quick Start


API services tied to entitled Adobe products (e.g. Campaign, Target, etc.) require a JSON Web Token (JWT) in order to retrieve access tokens for usage against authenticated endpoints. This document serves as a quickstart guide for first-time users.

### Things to check before you start:

You are an organizational admin for your enterprise organization with the appropriate Adobe products entitled. (Check at https://adminconsole.adobe.com/{your_org_id}@AdobeOrg/overview)

### Steps to get a JWT and an access token:

1. Create a new integration in Adobe I/O Console [https://console.adobe.io/integrations](https://console.adobe.io/integrations)

![Create Integration](https://lh4.googleusercontent.com/l91IHbNA9_pPJCzCgtvyh2D49u-pHz9Xz8CNAmIRe1xrMYl_O0p4pUkVR-aZowEt0pGL-1DIuAzKlVspNVAWjA3XpFN-OrJg8sxJ13Cw84WhKXrhLcXzraycY4A-mPVIdrTzziQd)

2. Subscribe to an entitled product (e.g. Campaign)

![Subscribe Service](https://lh4.googleusercontent.com/YTKiIbe7uWsmKQTsS-NsWE3LaMzFmNxmNvI0IrhJFHK8eD7os7maldAct3KiW9LlMCmJ0yaBW1mt7S2GGwg2lQft9Me1Ol9D84iJlGj1Uf7KnKsOSPPZ9JV5Gpq1gbTK_MRUztUR)

3. Create a private key and public certificate. Make sure you store this securely.

`openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:2048 -keyout private.key -out certificate_pub.crt`

4. Upload the public certificate (certificate_pub.crt) as a part of creating the integration.

![Upload Public Certificate](https://lh6.googleusercontent.com/7i7Nl-UZJPhO9un5enA-9DvuBeEjCBR53ES8sj_Gi_o0o0LVcUO_zOSjRuXKBRP9dnTmeo7Z4MCCdrqFAEMAOEaxxfFtFTEAlSmlKM0n0sBMGfBClKPE7zR4dU43zMJcsjWySp0c)

5. Your integration should now be created with the appropriate public certificate and claims.

![Integration Created](https://lh4.googleusercontent.com/dyFyXfUtzYkJU3JzSNW13mLVMKlObJML-5jduKJSuwTNcl-iSGj8UgkRiTb8toohXMtxmHQk9HTBLTCIzY_8fIPUo2Twy10bli7GyPy5q_BMZh8hzC3GdICWAP4ksPxoLndl8STq)

6. Go to the JWT tab and paste in you private key to generate a JWT.

![JWT Tab](https://lh6.googleusercontent.com/VIU-FDOKY9HHGum9arQaWXgColnJrK7qaiC0JQ_7Oh-m5O3HGYkDQWBlkGgOr_bY4ppNYYxJbezNNeSzQbzdpmmZEQi9Z0966BYwWNfifxeULNzbf1_m2gZ_xzxw1AzHFXkRrl1_)

7. Use the sample curl to get your first access token. 

![Get Access Token](https://lh5.googleusercontent.com/E2ORnk9PjMCa3XcQNmt1E-qdgw546NZil21e8s5S3Cry5cSRN0FV-Ep9gKBpPq82S5dQfeZ3is7b4d_FT90tDexPry6E3OoUrMnPXFywjmqwjJiVjmLuKioBpMBGVvRdeuOzgTJS)

The example curl sends a POST request to [https://ims-na1.adobelogin.com/ims/exchange/jwt](https://ims-na1.adobelogin.com/ims/exchange/jwt) with the following parameters.

| Parameter     | Description|
|---------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| client_id     | The API key generated for your integration. Find this on the I/O Console for your integration.|
| client_secret | The client secret generated for your integration. Find this on the I/O Console for your integration.|
| jwt_token     | A base-64 encoded JSON Web Token that encapsulates the identity of your integration, signed with a private key that corresponds to a public key certificate attached to the integration. Generate this on the I/O Console in the JWT Tab for your integration.|

You now have your access token. Look up documentation for the specific API service you’re hitting authenticated endpoints for to find what other parameters are expected. Most of them need an x-api-key which will be the same as your client id.
