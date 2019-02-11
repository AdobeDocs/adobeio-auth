
# JWT Authentication

To establish a secure service-to-service Adobe I/O API session, you must create a JSON Web Token (JWT) that encapsulates the identity of your integration, and exchange it for an access token. Every request to an Adobe service must include the access token in the Authorization header, along with the API Key (Client ID) that was generated when you created the integration in the [Adobe I/O Console](https://console.adobe.io/).

- A typical access token is valid for 24 hours after it is issued.
- You can request multiple access tokens. Previous tokens are not invalidated when a new one is issued. You can authorize requests with any valid access token. This allows you to overlap access tokens to ensure your integration is always able to connect to Adobe.
