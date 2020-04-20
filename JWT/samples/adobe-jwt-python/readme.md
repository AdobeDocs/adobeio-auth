This is a simple example of how to get an access token in python

You will need to install the necessary dependencies
```pip install pyjwt cryptography configparser```

You will also need to replace all of the variables in the ini file encapsulated in {} with your own values.

To get your OrgID, visit the Analytics 2.0 Swaggger here: https://adobedocs.github.io/analytics-2.0-apis/
Then log in, scroll down to the users category listing and expand it. 
Select the users/me endpoint and execute it. 
You'll see the OrgID in the request URL like this: https://analytics.adobe.io/api/{YourOrgID}/users/me
