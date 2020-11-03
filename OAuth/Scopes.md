# OAuth 2.0 Scopes

OAuth scopes govern the access and privileges an Adobe user will grant authorization to a third-party application for. As an application developer, you will choose the set of scopes that a user will encounter in an authorization approval screen by specifying the scopes as part of the OAuth authorization request. 

- [Identity scopes](#identity-scopes)
- [Creative Cloud](#creative-cloud)
- [Experience Cloud](#experience-cloud)
- [Scope Definitions](#scope-definitions)
    - [openid](#openid)
    - [creative_sdk](#creative_sdk)

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

## Scope Definitions

The scope determines the type of access to protected resources for which an application can be granted authorization. Scopes are aggregates of specific attributes.  

### openid

Access Adobe user profile information. 

|Attribute|User Information Returned|
|---|---|
|`userId`|Unique user ID.|
|`family_name`|Surname or family name.|
|`phoneNumber`|Telephone number (NULL if unavailable).|
|`email_verified`|Verification status of primary email address.|
|`countryCode`|Country component of an ISO2 locale code.|
|`name`|Full name.|
|`given_name`|Given name.|
|`email`|Primary email address.|
|`preferred_languages`|An array of locale codes indicating preferred language settings.|
|`account_type`|The account_type with one of the values: type1 (individual), type2 (enterprise), type3 (federated). See [Adobe Identity Types](https://helpx.adobe.com/enterprise/using/identity.html) for details.|

### creative_sdk

Intended for use specifically with the Creative SDK. This is an extension of the `openid` scope, so the following is a list of attributes *in addition* to the attributes available in the `openid` scope. 

|Attribute|User Information Returned|
|---|---|
|`userId`|Unique ID|
|`service_accounts`|An array of service account objects used to provide permissions to Creative Cloud services|
