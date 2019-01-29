# OAuth Integration

If your application needs to access Adobe services or content, you'll need a set of client credentials to authenticate your app and your user, and authorize access. The type of integration you are building will determine the type of client credentials you will need.

To obtain client credentials for an app that accesses services on behalf of an **end-user**, you'll need to create an **OAuth Integration** using the [Adobe I/O Console](https://console.adobe.io/). You can integrate your app with more than one Adobe service. This article will walk you through the steps to set up an OAuth integration.

OAuth allows your end users to sign in to your integration with an Adobe ID. With an OAuth token, your integration will be able to access Adobe services or content on behalf of the logged in user.

If your integration needs to access Adobe services or content on behalf of an organization (an Adobe enterprise organization), check out the **[Service Account Authentication](/AuthenticationOverview/ServiceAccountIntegration.md)**.

This article will walk you through the steps to set up an **OAuth integration**.

## OAuth Integration Workflow
[Step 1: Subscribe to an Adobe Service](#step-1-subscribe-to-an-adobe-service)

[Step 2: Configure an API Key Integration](#step-2-configure-an-api-key-integration)

[Step 3: Try It](#step-3-try-it)

### Step 1: Subscribe to an Adobe Service

- To create a new API Key integration, sign in to the [Adobe I/O Console](https://console.adobe.io/) with your Adobe ID, and click New Integration. (Notice that you may also choose existing integrations and edit their details from here.)

<kbd>![oauth-0](../Images/oauth-0.png)</kbd>

- Choose the type of service you want to include in your integration. You can get API access to several Adobe services or subscribe to real-time events. An integration can access multiple services and event sources. Simply perform these steps for each service or event you want to add to your integration.

<kbd>![oauth-2](../Images/oauth-2.png)</kbd>

- Select **Access an API** to create an integration that will access an Adobe product API or service, you will have an opportunity to subscribe to additional services and events once you have created the integration.

- Choose the service or event source that you would like to add to your integration. APIs and products available through Adobe I/O are typically listed by cloud.

<kbd>![oauth-1](../Images/oauth-1.png)</kbd>

- To update an existing integration, simply select it and click **Continue**.

- If you would like to create a brand new integration, select that option and click **Continue**.

### Step 2: Configure an API Key Integration

- The configuration page lets you provide all of the required details for a new integration, or add new information to update an existing integration. On this page:

<kbd>![oauth-3](../Images/oauth-3.png)</kbd>

|Integration Details| |
-----|----
`Name`|Enter a unique name to easily identify your integration
`Description`|Provide a brief description about this integration. If you have multiple applications or access multiple services, you can use these properties to better organize your integrations.
`Platform`|Select a platform on which the integration is intended to be used: `iOS`,`Android`,`Web`
`Default Redirect URI`|After a user successfully authorizes an application, the authorization server will redirect the user back to the application with either an authorization code in the URL. Because the redirect URL will contain sensitive information, it is critical that the service doesn’t redirect the user to arbitrary locations. *(HTTPS required)*
`Redirect URI pattern`|A comma seperated list of URI patterns, to validate additional custom redirect uri passed along with Authorization request. *(HTTPS required)* e.g. `https://www\\.myapp\\.com` will allow redirect uris like `https://www.myapp.com/OAuth/callback`

- Tip: Give your integrations accurate and descriptive names. Integrations are shared with developers within your organization, so choose a name that is clear and easily understood. Generic names like My Test App are discouraged.

- Click **Create integration**.

- When creation is confirmed, visit the overview section for your new integration. The overview section contains the newly generated API Key, and allows you to subscribe to additional services or events.

<kbd>![oauth-4](../Images/oauth-4.png)</kbd>



### Step 3: Try It

- Generate an access token using [OAuth 2.0 Playground](https://adobeioruntime.net/api/v1/web/io-solutions/adobe-oauth-playground/oauth.html)
