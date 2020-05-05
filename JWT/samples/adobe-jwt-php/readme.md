# PHP JWT Provider and auth example

This is a PHP implementation to prepare JWT and shows an example login to AdobeIO to obtain `access_token`, it does not use any external library and calls `openssl_sign` and other functions available in PHP to build JWT.

The method `doAdobeIOAuth($jwt,$client_id,$client_secret)` should be used as reference for login, it uses prepared JWT to do an authentication on AdobeIO and fetch `access_token`.

**To Run**
1. Download and extract the repo

2. Run PHP AccessTokenProvider.php to auth and get AdobeIO Access Token

3. Execute below commands

    **Usage**
    
    ```AccessTokenProvider.php -i <client-id> -s <client-secret> -k <key-file> -u <issuer> -b <subject> -c <metascopes, comma separated> -e <exp time, default 1 Day>```

    **Example**
    
    ```php JWT/samples/adobe-jwt-php/AccessTokenProvider.php -i"e1b8b4c4109c48....." -s"965f8635-........" -k"/Path/of/private.key" -u"DD0E3......@AdobeOrg" -b"D36......@techacct.adobe.com" -c"ent_reactor_sdk,ent_adobeio_sdk" -e"1 day"```
