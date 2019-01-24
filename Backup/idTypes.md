# Adobe-supported identity types

Adobe uses an underlying identity management system to authenticate and authorize users. If you're using named licensing or are planning to provide access to services, using identities is a requirement. Adobe supports three identity or account types; they use an email address as the user name.

## Adobe ID (Type 1)
Adobe ID is created, owned, and managed by the end user. Adobe performs the authentication and the end user manages the identity. Users retain complete control over files and data associated with their ID. Users can purchase additional products and services from Adobe. Admins invite users to join the organization, and can remove them. However, users cannot be locked out from their Adobe ID accounts. The admin can't delete or take over the accounts. No setup is necessary before you can start using Adobe IDs.

Adobe recommends Adobe IDs for the following requirements:

* To enable users to create, own, and manage their identities.
* To allow users to purchase or sign up for other Adobe products and services.
* When users are expected to use other Adobe services, which do not currently support Enterprise or Federated IDs.
* When users already have Adobe IDs, and associated data such as files, fonts, or settings. 
* In Higher Education settings so that adult students can easily retain the same Adobe ID and account content upon graduation.
* If you have contractors and freelancers who do not use email addresses on domains you control.

## Enterprise ID (Type 2)
Enterprise ID is created, owned, and managed by an organization. Adobe hosts the Enterprise ID and performs authentication, but the organization maintains the Enterprise ID. End users cannot sign up and create an Enterprise ID, nor can they sign up for additional products and services from Adobe using an Enterprise ID.

Admins create an Enterprise ID and issue it to a user. Admins can revoke access to products and services by taking over the account, or deleting the Enterprise ID to permanently block access to associated data.

Adobe recommends Enterprise IDs for the following requirements:

* To maintain strict control over apps and services available to a user.
* For emergency access to files and data associated with an ID.
* To have the ability to completely block or delete a user account.
* In all K-12 user settings to ensure compliance with student privacy and other relevant laws.

## Federated ID (Type 3)
Federated ID is created and owned by an organization, and linked to the enterprise directory via federation. The organization manages credentials and processes Single Sign-On via a SAML2 Identity Provider (IdP).

Adobe recommends Federated IDs for the following requirements:

* To provision users based on your organization's enterprise directory.
* To manage authentication of users.
* To maintain strict control over apps and services available to a user.
* To allow users to use the same email address to sign up for an Adobe ID.
* In all K-12 user settings to ensure compliance with student privacy and other relevant laws.