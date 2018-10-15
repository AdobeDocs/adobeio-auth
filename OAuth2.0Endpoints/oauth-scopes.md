# OAuth 2.0 Scopes

OAuth scopes govern the access and privileges an Adobe user will grant authorization to a third-party application for. As an application developer, you will choose the set of scopes that a user will encounter in an authorization approval screen by specifying the scopes as part of the OAuth authorization request. 

<!-- doctoc command: doctoc . --title "## Contents" --entryprefix 1. --gitlab --maxlevel 3 -->

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
## Contents

1. [Creative Cloud](#creative-cloud)
    1. [Adobe Stock](#adobe-stock)
1. [Experience Cloud](#experience-cloud)
    1. [Adobe Analytics](#adobe-analytics)
1. [Scope Definitions](#scope-definitions)
    1. [openid](#openid)
    1. [creative_sdk](#creative_sdk)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Creative Cloud
Scopes for Creative Cloud OAuth based APIs

### Adobe Stock
**Scopes**: `openid,creative_sdk`



## Experience Cloud
Scopes for Experience Cloud OAuth based APIs

### Adobe Analytics
**Scopes**: `openid,AdobeID,read_organizations,additional_info.projectedProductContext,additional_info.job_function`



## Scope Definitions
The scope determines the type of access to protected resources an application can be granted authorization to. Scopes are aggregates of specific attributes.  

### openid

One-stop-shop scope for accessing Adobe user profile information. 

|Attribute|User Information Returned|
|---|---|
|userId|Unique ID|
|first_name|Given name|
|last_name|Surname or family name|
|phoneNumber|Telephone number (NULL if unavailable)|
|emailVerified|Verification status of primary email address|
|countryCode|Country component of an ISO2 locale code|
|name|Full name|
|displayName|Display name|
|email|Primary email address|
|preferred_languages|An array of locale codes indicating preferred language settings|
|account_type|The account_type with one of the values: type1 (individual), type2 (enterprise), type3 (federated). See [Adobe Identity Types](https://helpx.adobe.com/enterprise/using/identity.html) for details.|

### creative_sdk

Intended for use specifically with the Creative SDK. This is an extension of the `openid` scope, so the following is a list of attributes *in addition* to what's available in the `openid` scope. 

|Attribute|User Information Returned|
|---|---|
|userId|Unique ID|
|service_accounts|An array of service account objects used to provide permissions to Creative Cloud services|
