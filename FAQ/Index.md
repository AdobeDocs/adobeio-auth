# Frequently Asked Questions

- [Where can I find the sample code for OAuth authentication?](#where-can-i-find-the-sample-code-for-oauth-authentication-)
- [How to use the private key for generating a JWT?](#how-to-use-the-private-key-for-generating-a-jwt-)
- [What does Default Redirect URI and the Redirect URI Pattern means?](#what-does-default-redirect-uri-and-the-redirect-uri-pattern-means-)

### Where can I find the sample code for OAuth authentication?
Code samples are available for [NodeJS](https://github.com/AdobeDocs/adobeio-auth/tree/stage/OAuth/samples/adobe-auth-node) and [Python](https://github.com/AdobeDocs/adobeio-auth/tree/stage/OAuth/samples/adobe-auth-python).

### How do I use the private key for generating a JWT?
Please copy the full content of the private key including `-----BEGIN PRIVATE KEY-----` and `-----END PRIVATE KEY-----` to generate a correct JWT token.

### What does Default Redirect URI and the Redirect URI Pattern mean?
The default redirect URI is the URL where Adobe Identity Management Service (IMS) will send the authorization code after a successful login with Adobe. You will need that to make a call to the token endpoint to receive an access token.

The redirect URI pattern is a regex representation of allowed URLs to receive the authorization code. It is used when you pass the optional parameter `redirect_url` with your request.
