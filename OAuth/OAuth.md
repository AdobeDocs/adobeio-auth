# OAuth 2.0 Authentication

Adobe Cloud Platform APIs use the OAuth 2.0 protocol for authentication and authorization. Using Adobe OAuth 2.0, you can generate an access token which is used to make API calls from your web server or browser-based apps.

The basic Web OAuth 2.0 workflow will look like:

1. Your application **redirects** the user to Adobe along with the list of requested permissions
2. Adobe prompts the user with a login screen and informs the user of the requested permissions
3. The user decides whether to grant the permissions
4. Adobe sends a **callback** to your application to notify whether the user granted the permissions
5. After permissions are granted, your application retrieves tokens required to make API requests on behalf of the user

The process of providing secure access to protected resources has two stages, **authorization** and **authentication**. It is important to understand that they are separate concepts.

- **Authorization** is the process of granting permission to a user to access a protected resource. Because authentication is usually a prerequisite for granting access, these two terms often occur together.
- **Authentication** is the process of determining that a user is who she claims to be. Authentication can be checked by Adobe's own identity provider, the Identity Management Services (IMS).
