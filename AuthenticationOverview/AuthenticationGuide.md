# Adobe I/O Authentication Overview

Adobe is committed to privacy and security. Nearly all Adobe services require your application to authenticate through the Adobe Identity Management System (IMS) to receive client credentials. The client credentials determine the access and permissions granted to your application.

Any API that accesses a service, data or content on behalf of an end user authenticates using the OAuth or JSON Web Token standards.

Use the [Adobe Developer Console](https://www.adobe.com/go/devs_console_ui/) to obtain client credentials by creating a new **Project**. Once you create a project, you can add APIs that include **API Keys** (Client ID) and other access credentials. You can then obtain a secure access token from Adobe for each API session.

A project can include one or more services. In many cases, you will use the same client credentials to access multiple Adobe products and services. In addition to APIs, you may also add I/O Events and I/O Runtime to your projects so that your applications can access content and services in real-time.

To learn more about creating projects in Adobe Developer Console, read the [Console getting started guide](https://www.adobe.com/go/devs_console_getting_started).

## Types of Authentication

### [API Key Integration (Authorization Only)](APIKeyIntegration.md)
An API Key is the only client credential required for these services. These integrations do not need to pass an access token with each request.
e.g. Adobe Stock

### [OAuth Integration (OAuth 2.0 authentication flow)](OAuthIntegration.md)
If your integration needs to access content or a service on behalf of an end user, that user must be authenticated as well. Your integration will need to pass the OAuth token granted by the Adobe IMS.
e.g. Creative SDK, Photoshop, Adobe Analytics

### [Service Account Integration (JWT authentication flow)](ServiceAccountIntegration.md)
For service-to-service integrations, you will also need a JSON Web Token (JWT) that encapsulates your client credentials and authenticates the identity of your integration. You then exchange the JWT for the access token that authorizes access.
e.g. Adobe Campaign, Adobe Launch, Adobe Target
