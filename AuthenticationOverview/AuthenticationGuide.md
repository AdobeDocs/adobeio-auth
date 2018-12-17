# Adobe I/O Authentication Overview

Adobe is committed to privacy and security. Nearly all Adobe services require your application to authenticate through the Adobe Identity Management System (IMS) to receive client credentials. The client credentials determine the access and permissions granted to your application.

Any API that accesses a service, data or content on behalf of an end user authenticates using the OAuth and JSON Web Token standards.




Use the [Adobe I/O Console](https://console.adobe.io/) to obtain client credentials by creating a new **Integration**. When you create an Integration, you are assigned an **API Key** (client ID) and other access credentials. You can then obtain a secure access token from Adobe for each API session.

An integration can be subscribed to one or more services. In many cases, you will use the same client credentials to access multiple Adobe services. In addition to APIs, you may also subscribe your integration to I/O Events so that your applications can access content and services in real-time.

## Types of Authentication

### [API Key Integration (Integration Only)](/auth/AuthenticationOverview/APIKeyIntegration.md)
The [Adobe I/O Console](https://console.adobe.io/) is where you can generate an API Key, an important requirement to obtain client credentials.
e.g. Adobe Stock etc.

### [OAuth Integration (OAuth 2.0 authentication flow)](/auth/AuthenticationOverview/OAuthIntegration.md)
If your integration needs to access content or a service on behalf of an end user, that user must be authenticated as well. Your integration will need to pass the OAuth token granted by the Adobe IMS.
e.g. Creative SDK, Photoshop, Adobe Analytics, etc.

### [Service Account Integration (JWT authentication flow)](/auth/AuthenticationOverview/ServiceAccountIntegration.md)
For service-to-service integrations, you will also need a JSON Web Token (JWT) that encapsulates your client credentials and authenticates the identity of your integration. You exchange the JWT for the OAuth token that authorizes access.
e.g. Adobe Campaign, Adobe Launch, Adobe Target, etc.
