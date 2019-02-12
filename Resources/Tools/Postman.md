- [OAuth Access Token](#oauth-access-token)
- [JWT Access Token](#jwt-access-token)
- [A trick for Windows user](#a-trick-for-windows-user)

## OAuth Access Token

### Steps

1. Install [Postman](https://www.getpostman.com/downloads/)

2. Open Postman

3. Create a `new request`.

<kbd>![pm-1](/Images/PM_1.png)</kbd>

4. Click on `Authorization` and select `OAuth 2.0` from Type dropdown menu.

<kbd>![pm-2](/Images/PM_2.png)</kbd>

5. Click on `Get New Access Token`.

<kbd>![pm-3](/Images/PM_3.png)</kbd>

6. Copy your `API Key (Client ID)`, `Client Secret` and the `Default Redirect URI` from your Adobe I/O Console integration and paste it in the Postman `GET NEW ACCESS TOKEN` window. Assuming you are trying to get an access token for Adobe Analytics use below for scope: `openid,AdobeID,read_organizations,additional_info.projectedProductContext,additional_info.job_function`

*Note: If you are not sure about scope, use `openid` to proceed, it will generate an access token for you but certain APIs won't work without appropriate scopes.*

<kbd>![pm-4](/Images/PM_4.png)</kbd>

7. You will be prompted for login by Adobe. Login using your Adobe ID.

<kbd>![pm-5](/Images/PM_5.png)</kbd>

8. Your access token will be generated.

<kbd>![pm-6](/Images/PM_6.png)</kbd>
