
# JWT Authentication

To establish a secure service-to-service Adobe I/O API session, you must create a JSON Web Token (JWT) that encapsulates the identity of your integration, and exchange it for an access token. Every request to an Adobe service must include the access token in the Authorization header, along with the API Key (Client ID) that was generated when you created the integration in the [Adobe I/O Console](https://console.adobe.io/).


The basic Web JWT authentication workflow will look like:
1. Creat a JSON Web Token (JWT) using the **Private key, API Key, Client Secret, Technical Account ID, Org ID, Metascope and Expiration time**
2. Exchanging the JWT with token endpoint to get an access token

## Creating a JSON Web Token

A JSON web token for Service Account authentication requires a particular set of claims, and must be signed using a valid digital signing certificate. We recommend that you use one of the publicly available libraries or tools for building your JWT. Examples are provided for some popular languages.

- Required Claims for a Service Account JWT
- Sign and Encode your JWT
- Using JWT Libraries and Creation Tools

### Required Claims for a Service Account JWT
Your JWT must contain the following claims:

Claim |	Description
---- | ----
exp |	*Required*. The expiration parameter is a required parameter measuring the absolute time since 01/01/1970 GMT. You must ensure that the expiration time is later than the time of issue. After this time, the JWT is no longer valid. At maximum, the expiration period can be set up to 24 hours from time of issue. *Note: This is an expirtation time for the JWT token and not the access token. Access token expiration is set to 24 hours by default.*
iss |	*Required*. The issuer, your **Organization ID** from the Adobe I/O Console integration, in the format org_ident@AdobeOrg. Identifies your organization that has been configured for access to the Adobe I/O API. 
sub |	*Required*. The subject, your **Technical Account ID** from the Adobe I/o Console integration,  in the format: id@techacct.adobe.com.
aud |	*Required*. The audience for the token, in the format: https://ims-na1.adobelogin.com/c/**api_key**.
Configured claims | Required. The API-access claim configured for your organization: https://ims-na1.adobelogin.com/s/ent_dataservices_sdk.

The following is a sample payload to be signed and encoded.

```json
{
    "exp": 1550001438,
    "iss": "C74F69D7594880280.....@AdobeOrg",
    "sub": "6657031C5C095BB40A4.....@techacct.adobe.com",
    "https://ims-na1.adobelogin.com/s/ent_dataservices_sdk": true,
    "aud": "https://ims-na1.adobelogin.com/c/a64f5f10849a410a97ffdac8ae1....."
}
```

### Sign and Encode your JWT
The JWT must be signed and base-64 encoded for inclusion in the access request. The JWT libraries provide functions to perform these tasks.

- The token must be signed using the private key for a digital signing certificate that is associated with your API key. You can associate more than one certificate with an API key. If you do so, you can use the private key of any associated certificate to sign your JWT. For more information, see **Service Account Integration**.
- Adobe supports RSASSA-PKCS1-V1_5 Digital Signatures with SHA-2. The JWS algorithm ("alg") parameter value can be RS256, RS384, or RS512.
