# OAuth 2.0 Playground

The OAuth 2.0 Playground is an Adobe internet utility that enables developers to easily obtain an OAuth 2.0 access token for use in building and testing their integrations. Before you use the OAuth 2.0 Playground, you should already have created an integration you want to use for this purpose. The OAuth 2.0 Playground source code is also available for you to view and experiment with.

**Steps to obtain a token:**

1. Go to [OAuth 2.0 Playground](https://adobeioruntime.net/api/v1/web/io-solutions/adobe-oauth-playground/oauth.html)  
  
    <kbd>![op-1](../../Images/OP_1.png)</kbd>

2. Go to the [Adobe I/O Console](https://console.adobe.io)

3. Create an integration: choose to access an API, and then select the services with which you wish to integrate (such as Adobe Analytics > OAuth 2.0 Integration)

4. Provide the Redirect URI pattern as `https://runtime\\.adobe\\.io`  
  
    <kbd>![op-2](../../Images/OP_2.png)</kbd>

5. Copy your **API Key** and **Client Secret** from your Adobe I/O Console integration to OAuth 2.0 Playground.

6. Enter scopes as:  
  `openid,read_organizations,additional_info.projectedProductContext,additional_info.job_function`  
    
      <kbd>![op-3](../../Images/OP_3.png)</kbd>

7. Click **Generate Tokens.**

8. You will be prompted for login by Adobe. Log in using your Adobe ID.  
  
    <kbd>![op-4](../../Images/OP_4.png)</kbd>

9. Your tokens will be generated.  
  
    <kbd>![op-5](../../Images/OP_5.png)</kbd>
