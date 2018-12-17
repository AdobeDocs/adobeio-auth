# API Key Integration

A small collection of Adobe services (e.g. Adobe Stock) require authorization, but do not require authentication. These services can be called *“anonymously”* and typically provide consistent results regardless of the application or user that made the request. An **API Key** is the only client credential required for these services. These integrations do not need to pass an access token with each request.

This article will walk you through the steps to set up an **API Key integration**.

To obtain an API Key, you'll need to create an API Key Integration using the Adobe I/O Console, as described here.
If your integration needs to access Adobe services or content on behalf of a user or an Adobe enterprise organization, it needs additional credentials for authentication. For more information, check out the articles on **[OAuth Authentication](/auth/AuthenticationOverview/OAuthIntegration.md)** and **[Service Account Authentication](/auth/AuthenticationOverview/ServiceAccountIntegration.md)**.

## API Key Integration Workflow
[Step 1: Subscribe to a Service or Event Provider](#step-1-subscribe-to-a-service-or-event-provider)

[Step 2: Configure an API Key Integration](#step-2-configure-an-api-key-integration)

[Step 3: Secure your Client Credentials](#step-3-secure-your-client-credentials)


### Step 1: Subscribe to a Service or Event Provider

To create a new API Key integration, sign in to the [Adobe I/O Console](https://console.adobe.io/) with your Adobe ID, and click New Integration. (Notice that you may also choose existing integrations and edit their details from here.)

### Step 2: Configure an API Key Integration

### Step 3: Secure your Client Credentials
