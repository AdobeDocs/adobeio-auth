# OAuth Integration

If your application needs to access Adobe services or content, you&rsquo;ll need a set of client credentials to authenticate your app and your user and authorize access. The type of integration you are building will determine the type of client credentials you will need.

To obtain client credentials for an app that accesses services on behalf of an **end-user**, you&rsquo;ll need to create an **OAuth integration** using the [Adobe I/O Console](https://console.adobe.io/). You can integrate your app with more than one Adobe service. This article will walk you through the steps to set up an OAuth integration.

OAuth allows your end users to sign in to your integration with an Adobe ID. With an OAuth token, your integration will be able to access Adobe services or content on behalf of the logged-in user.

If your integration needs to access Adobe services or content on behalf of an organization (an Adobe enterprise organization), check out the **[Service account integration](ServiceAccountIntegration.md)**.

This article will walk you through the steps to set up an **OAuth integration**.

## OAuth Integration Workflow
[Step 1: Subscribe to an Adobe service](#step-1-subscribe-to-an-adobe-service)

[Step 2: Configure an OAuth integration](#step-2-configure-an-oauth-integration)

[Step 3: Try It](#step-3-try-it)

### Step 1: Subscribe to an Adobe service
1. To create a new API Key integration, sign in to the [Adobe I/O Console](https://console.adobe.io/) with your Adobe ID, and select **New Integration**. (Notice that you may also choose existing integrations and edit their details from here.)  
  <kbd>![oauth-0](../Images/oauth-0.png)</kbd>

2. Choose the type of service you want to include in your integration. You can get API access to several Adobe services or subscribe to near real-time events. An integration can access multiple services and event sources. Simply perform these steps for each service or event you want to add to your integration.  
<kbd>![oauth-2](../Images/oauth-2.png)</kbd>

3. Select **Access an API** to create an integration that will access an Adobe product API or service. You will have an opportunity to subscribe to additional services and events once you have created the integration.

4. Choose the service or event source that you would like to add to your integration. APIs and products available through Adobe I/O are typically listed by cloud.  
  <kbd>![oauth-1](../Images/oauth-1.png)</kbd>

    - To update an existing integration, simply choose it and select **Continue**.
    - If you would like to create a brand-new integration, choose that option and select **Continue**.

### Step 2: Configure an OAuth integration

1. The configuration page lets you provide all of the required details for a new integration, or add new information to update an existing integration.  On the page shown below, enter your integration details.  
  **Tip:** Give your integrations accurate and descriptive names. Integrations are shared with developers within your organization, so choose a name that is clear and easily understood. Generic names like My Test App are discouraged.  
  
   <kbd>![oauth-3](../Images/oauth-3.png)</kbd>

|Detail | Description |
|---|---|
| Name | Enter a unique name to easily identify your integration. |
| Description | Provide a brief description about this integration. If you have multiple applications or access multiple services, you can use these properties to better organize your integrations. |
| Platform | Select a platform on which the integration is intended to be used: `iOS`, `Android`, or `Web`. |
| Default redirect URI | After a user successfully authorizes an application, the authorization server will redirect the user back to the application with an authorization code in the URI. Because the redirect URI will contain sensitive information, it is critical that the service doesn’t redirect the user to arbitrary locations. _(HTTPS required)_ |
| Redirect URI pattern | A comma-separated list of URI patterns to validate additional custom redirect URIs passed along with an authorization request. _(HTTPS required)_ For example, `https://www\\.myapp\\.com` will allow redirect URIs like `https://www.myapp.com/OAuth/callback`. |

2. Select **Create integration**.

3. When creation is confirmed, visit the Overview section for your new integration. The overview section contains the newly generated API Key and allows you to subscribe to additional services or events.  
  
    <kbd>![oauth-4](../Images/oauth-4.png)</kbd>



### Step 3: Try it

Generate an access token using [OAuth 2.0 Playground](../Resources/Tools/OAuthPlayground.md).
