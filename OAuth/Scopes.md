# OAuth 2.0 Scopes

OAuth scopes govern the access and privileges an Adobe user will grant authorization to a third-party application for. As an application developer, you will choose the set of scopes that a user will encounter in an authorization approval screen by specifying the scopes as part of the OAuth authorization request. 

- [Identity scopes](#identity-scopes)
- [Creative Cloud](#creative-cloud)
- [Experience Cloud](#experience-cloud)

## Identity scopes

|Scope|Consent description|Details|
|---|---|---|
|`openid`|Can access user account and read a unique identifier|Mandatory scope to enable authorization flows.|
|`email`|Can read user email address|Returns `email` and `email_verified` claims.|
|`address`|Can read user postal address|Returns `address` claim. Currently this contains only the country code.|
|`profile`|Can read basic user profile, including information like `name`|Returns `name`, `family_name`, `given_name`, `account_type` claims.|
|`offline_access`|The app can access the data user has given permission to, even when user is not using the app|Allows the return of a refresh token.|

## Creative Cloud

Scopes for Creative Cloud OAuth based APIs

APIs | Scopes
---|---
Adobe Stock | `openid`
Creative SDK | `openid,creative_sdk`
Photoshop | `openid,creative_sdk`
Lightroom | `openid,creative_sdk`

## Experience Cloud

Scopes for Experience Cloud OAuth based APIs

APIs | Scopes
---|---
Adobe Analytics | `openid,AdobeID,read_organizations,additional_info.projectedProductContext,additional_info.job_function`