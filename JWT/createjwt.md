# Creating a JSON Web Token

A JSON web token for Service Account authentication requires a particular set of claims, and must be signed using a valid digital signing certificate. We recommend that you use one of the publicly available libraries or tools for building your JWT. Examples are provided for some popular languages.

* [Required Claims for a Service Account JWT](createjwt.md#required-claims-for-a-service-account-jwt)
* [Sign and Encode your JWT](createjwt.md#sign-and-encode-your-jwt)
* [Using JWT Libraries and Creation Tools](createjwt.md#using-jwt-libraries-and-creation-tools)

## Required Claims for a Service Account JWT

Your JWT must contain the following claims:

Claim | Description
----- | -----------
**exp** | Required. The expiration parameter is a required parameter measuring the absolute time since 01/01/1970 GMT. You must ensure that the expiration time is later than the time of issue. After this time, the JWT is no longer valid. At maximum, the expiration period can be set up to 24 hours from time of issue.
**iss** | Required. The issuer, your organization ID in the format _org_ident@AdobeOrg_. Identifies your organization that has been configured for access to the Adobe I/O API.
**sub** | Required. The subject, your API client account ID in the format: _id@techacct.adobe.com_.
**aud** | Required. The audience for the token, in the format: **https://ims-na1.adobelogin.com/c/api_key** configured claims	Required. The API-access claim configured for your organization: **https://ims-na1.adobelogin.com/s/ent_user_sdk**.
**jti** | Optional. A unique identifing string for the token, if configured for your organization. If required, you must use a string created from a decimal number greater than any valued used before, in order to prevent replay attacks. Otherwise, the request fails. To ensure an acceptable value, you can convert the current Unix time (seconds since 1970) to a string.

The following is a sample payload to be signed and encoded.

```
{
  "sub": "12345667EDBA435@techacct.adobe.com",
  "iss": "8765432DEAB65@AdobeOrg",
  "exp": 1473901205,
  "aud": "https://ims-na1.adobelogin.com/c/1234-5678-9876-5433",
  "https://ims-na1.adobelogin.com/s/ent_user_sdk": true,
  "jti": "1470000000"
}
```
## Sign and Encode your JWT

The JWT must be signed and base-64 encoded for inclusion in the access request. The JWT libraries provide functions to perform these tasks.

- The token must be signed using the private key for a digital signing certificate that is associated with your API key. You can associate more than one certificate with an API key. If you do so, you can use the private key of any associated certificate to sign your JWT. For more information, see [Public Key Certificates for JWT](createcert.md).
- Adobe supports RSASSA-PKCS1-V1_5 Digital Signatures with SHA-2. The JWS algorithm ("alg") parameter value can be RS256, RS384, or RS512.

## Using JWT Libraries and Creation Tools

Most modern languages have JWT libraries available. We recommend you use one of these libraries (or other JWT-compatible libraries) before trying to hand-craft the JWT.

Other JWT tools are publicly available, such as the [JWT decoder](http://jwt-decoder.herokuapp.com/jwt/decode), a handy web-based decoder for Atlassian Connect JWTs.

Examples are provided for several popular languages.

| Language | Library                     | Example                                                                                                                                                           |
| -------- | --------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------- |
Java | `atlassian-jwt` `jsontoken`  | [Creating JWTs for Java Apps](./createjwt/jwt_java.md)
Node.js | `jsonwebtoken` | [Creating JWTs for Node.js Apps](./createjwt/jwt_nodeJS.md)
Python | `pyjwt` | For an example Python script that creates a JWT, see the [User Management Walkthrough](https://www.adobe.io/apis/cloudplatform/usermanagement/docs/samples.html).

### Additional JWT Libraries and Creation Tools

The following JWT libraries are available, in addition to the Java, Node.js, and Python libraries for which we have provided examples.

| Language | Library                             |
| -------- | ----------------------------------- |
| Ruby     | `ruby-jwt`                          |
| PHP      | `firebase php-jwt` `luciferous jwt` |
| .NET     | `jwt`                               |
| Haskell  | `haskell-jwt`                       |
