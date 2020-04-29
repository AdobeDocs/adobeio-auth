# JWT (Service Account) Authentication

To establish a secure service-to-service Adobe I/O API session, you must create a JSON Web Token (JWT) that encapsulates the identity of your integration, and then exchange it for an access token. Every request to an Adobe service must include the access token in the `Authorization` header, along with the API Key (Client ID) that was generated when you created the [Service Account Integration](../AuthenticationOverview/ServiceAccountIntegration.md) in the [Adobe Developer Console](https://www.adobe.com/go/devs_console_ui/).

## Authentication Workflow

- [Creating a JSON Web Token](#creating-a-json-web-token)
- [Exchanging JWT to retrieve an access token](#exchanging-jwt-to-retrieve-an-access-token)

## Creating a JSON Web Token

A JSON Web Token for Service Account authentication requires a particular set of claims, and must be signed using a valid digital signing certificate. We recommend that you use one of the publicly available libraries or tools for building your JWT. Examples are provided for some popular languages.

### Required Claims for a Service Account JWT

Your JWT must contain the following claims:

| Claim      | Description|
|---|---|
| exp        | _Required_. The expiration parameter is a required parameter measuring the absolute time since 01/01/1970 GMT. You must ensure that the expiration time is later than the time of issue. After this time, the JWT is no longer valid. **Recommendation**: Have a very short lived token (a few minutes) - such that it expires soon after it has been exchanged for an IMS access token. Every time a new access token is required, one such JWT is signed and exchanged. This is secure approach. Longer lived tokens that are re-used to obtain access tokens as needed are not recommended. |
| iss        | _Required_. The issuer, your **Organization ID** from the Adobe Developer Console integration, in the format `org_ident@AdobeOrg`. Identifies your organization that has been configured for access to the Adobe I/O API.|
| sub        | _Required_. The subject, your **Technical Account ID** from the Adobe Developer Console integration, in the format: `id@techacct.adobe.com`.|
| aud        | _Required_. The audience for the token, your **API Key** from the Adobe Developer Console integration, in the format: `https://ims-na1.adobelogin.com/c/api_key`.|
| Metascopes | _Required_. The API-access claim configured for your organization: [JWT Metascopes](Scopes.md), in the format: `"https://ims-na1.adobelogin.com/s/meta_scope": true`|

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

- The token must be signed using the private key for a digital signing certificate that is associated with your API key. You can associate more than one certificate with an API key. If you do so, you can use the private key of any associated certificate to sign your JWT. For more information about private key/public certificate, see [Create a public key certificate](../AuthenticationOverview/ServiceAccountIntegration.md#step-2-create-a-public-key-certificate).

**Algorithm**: **RS256** (RSA Signature with SHA-256) is an asymmetric algorithm, and it uses a public/private key pair: the identity provider has a private (secret) key used to generate the signature, and the consumer of the JWT (i.e. Adobe Developer Console) gets a public key to validate the signature.

### Using JWT Libraries and Creation Tools

Most modern languages have JWT libraries available. We recommend you use one of these libraries (or other JWT-compatible libraries) before trying to hand-craft the JWT.

Other JWT tools are publicly available, such as the [JWT.IO](https://jwt.io/), a handy web-based encoder/decoder for JWTs.

Examples are provided for several popular languages.

| Language | Library                     |
| -------- | --------------------------- |
| Java     | `atlassian-jwt` `jsontoken` |
| Node.js  | `jsonwebtoken`              |
| Python   | `pyjwt`                     |

### Additional JWT Libraries and Creation Tools

The following JWT libraries are available, in addition to the Java, Node.js, and Python libraries for which we have provided examples.

| Language | Library                             |
| -------- | ----------------------------------- |
| Ruby     | `ruby-jwt`                          |
| PHP      | `firebase php-jwt` `luciferous jwt` |
| .NET     | `jwt`                               |
| Haskell  | `haskell-jwt`                       |

## Exchanging JWT to retrieve an access token

To initiate an API session, use the JWT to obtain an access token from Adobe by making a POST request to Adobe Identity Management Service (IMS).

- Send a POST request to:

`https://ims-na1.adobelogin.com/ims/exchange/jwt`

- The body of the request should contain URL-encoded parameters with your Client ID (API Key), Client Secret, and JWT:

`client_id={api_key_value}&client_secret={client_secret_value}&jwt_token={base64_encoded_JWT}`

### Request parameters

Pass URL-encoded parameters in the body of your POST request:

| Parameter     | Description                                                                                                                                                                              |
| ------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| client_id     | The API key generated for your integration.                                                                                                                                              |
| client_secret | The client secret generated for your integration.                                                                                                                                        |
| jwt_token     | A base-64 encoded JSON Web Token that encapsulates the identity of your integration, signed with a private key that corresponds to a public key certificate attached to the integration. |

### Responses

When a request has been understood and at least partially completed, it returns with HTTP status 200. On success, the response contains a valid access token. Pass this token in the Authorization header in all subsequent requests to an Adobe service.

A failed request can result in a response with an HTTP status of 400 or 401 and one of the following error messages in the response body:

<table>
	<thead>
		<tr>
			<th>Response</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>400 invalid_client</td>
			<td>Integration does not exist. This applies both to the <code>client_id</code> parameter and the <code>aud</code> in the JWT. The <code>client_id</code> parameter and the <code>aud</code> field in the JWT do not match.</td>
		</tr>
		<tr>
			<td>401 invalid_client</td>
			<td>Integration does not have the <code>exchange_jwt</code> scope. This indicates an improper client configuration. Contact the Adobe I/O team to resolve it. The client ID and client secret combination is invalid.</td>
		</tr>
		<tr>
			<td>400 invalid_token</td>
			<td>JWT is missing or cannot be decoded. JWT has expired. In this case, the <code>error_description</code> contains more details. The <code>exp</code> or <code>jti</code> field of the JWT is not an integer.</td>
		</tr>
		<tr>
			<td>400 invalid_signature</td>
			<td>The JWT signature does not match any certificates attached to the integration. The signature does not match the algorithm specified in the JWT header.</td>
		</tr>
		<tr>
			<td>400 invalid_scope</td>
			<td>Indicates a problem with the requested scope for the token. Specific scope problems can be:
				<ul>
					<li>Metascopes in the JWT do not match metascopes in the binding.</li>
					<li>Metascopes in the JWT do not match target client scopes.</li>
					<li>Metascopes in the JWT contain a scope or scopes that do not exist.</li>
					<li>The JWT has no metascopes.</li>
				</ul>
			</td>
		</tr>
		<tr>
			<td>400 bad_request</td>
			<td>The JWT payload can be decoded and decrypted, but its contents are incorrect. This can occur when values for fields such as <code>sub</code>, <code>iss</code>, <code>exp</code>, or <code>jti</code> are not in the proper format.</td>
		</tr>
	</tbody>
</table>

### Example

```
========================= REQUEST ==========================
POST https://ims-na1.adobelogin.com/ims/exchange/jwt
-------------------------- body ----------------------------
client_id={myClientId}&client_secret={myClientSecret}&jwt_token={myJSONWebToken}
------------------------- headers --------------------------
Content-Type: application/x-www-form-urlencoded
Cache-Control: no-cache
```
