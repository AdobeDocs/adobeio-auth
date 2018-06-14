# JWT Authentication using Postman Environment Template and Collections

## What is JWT authentication? What are service account type integrations? 
JSON web tokens (JWT) are 

## Why use Postman?


## Required setup

### Private/public key pair

### Service account type integration on I/O Console

### Postman installation

## Postman Collections


## Postman Environment Template


## Using [`jsrsasign`](https://github.com/kjur/jsrsasign) to create a JWT and request for an access token


## Using the access token to make authenticated calls


## Thanks
Thanks to Klaasjan Tukker for the [original article](https://medium.com/adobetech/using-postman-for-jwt-authentication-on-adobe-i-o-7573428ffe7f). 



## Introduction
Working in the Adobe Cloud Platform — Data Services team, I am responsible for the API First strategy and availability of our APIs to Adobe and third party developers. We are using the Adobe I/O Gateway to provide developers access to these platform APIs. For authentication for third party developers, Adobe I/O is using an JWT workflow. Frequently I am testing APIs in [Postman] (https://www.getpostman.com/) and I was looking for a way to execute the full authentication flow, including the generation of JWT request, signed with SHA-256. The [Adobe I/O Console](https://console.adobe.io/) allows you to generate a new JWT token, but I was looking to do everything inside of Postman. Merely there was not a complete recipe available to do this, so I rolled up my sleeves, Googled and Stackoverflowed my way through the web to put together the approach documented in this article. This article was originally written for the [Adobe I/O Blog, hosted on Medium](https://medium.com/adobe-io/using-postman-for-jwt-authentication-on-adobe-i-o-7573428ffe7f).


## Setup I/O Integration
To integrate with Adobe solutions, you can find all related information and documentation on the [adobe.io] (http://www.adobe.io) website. In this case we'll be using the service-to-service pattern (using JWT). This requires to setup a new integration using [Adobe I/O Console](https://console.adobe.io/). The steps to setup a new integration can be found [here](https://www.adobe.io/apis/cloudplatform/console/authentication/gettingstarted.html) or [here](https://www.adobe.io/apis/cloudplatform/dataservices/tutorials/alltutorials.html#!api-specification/markdown/narrative/tutorials/authenticate_to_acp_tutorial/authenticate_to_acp_tutorial.md).
Once your integration is setup, you can use the details inside of Postman. In my case, I configured an integration to call the APIs for [Adobe Cloud Platform - Data Services](https://www.adobe.io/apis/cloudplatform/dataservices.html) and the screenshot with the results are below. I will use the details listed on this screen later.
![I/O integration details](https://github.com/ktukker/adobe.io-jwt-postman/raw/master/images/io_integration_details.png)

## Downloading and configuring Postman
Postman is a very convenient tool to execute REST API calls, manage different libraries of pre-defined calls as well as different environments. I am working with various environments (internal development and staging environments, next to our production environment) and multiple customer account settings.

* To get started, download Postman from the official [website] (https://www.getpostman.com/).
* Download the Postman [collection](https://raw.githubusercontent.com/ktukker/adobe.io-jwt-postman/master/postman/collections/Adobe%20I-O.postman_collection.json) and [environment template](https://github.com/ktukker/adobe.io-jwt-postman/raw/master/postman/environments/JWT%20-%20Template.postman_environment.json) from the [Github repository](https://github.com/ktukker/adobe.io-jwt-postman).

In Postman you can add the downloaded collection by clicking on the "Import" button on the top left and select the collection you want to import. 
![Imported Postman Collection](https://github.com/ktukker/adobe.io-jwt-postman/raw/master/images/postman_collection.png)

After importing the collection, import the pre-configured environment template. In the top right, click on the "Gear" icon and click on the "Import" button. Now you can select the downloaded environment template.
![Imported Postman Collection](https://github.com/ktukker/adobe.io-jwt-postman/raw/master/images/postman_environment_template.png)
You are now ready to configure your environment based on the settings provided in IO Console.

## Setting up environments
The Environments feature of Postman allows you to efficiently switch between multiple pre-configured environments. The [environment template](https://github.com/ktukker/adobe.io-jwt-postman/raw/master/postman/environments/JWT%20-%20Template.postman_environment.json) has pre-configured variable names that need to be populated with the correct values found in the I/O Console screen (see above). 

* Copy your values into the pre-configured template

The value for ```meta_scope``` might be more challenging to find. This setting is different per solution / product that you integrate with. You can find this in the I/O Console for your created integration under the "JWT" tab. See highlighted element in the screenshot below.
![Imported Postman Collection](https://github.com/ktukker/adobe.io-jwt-postman/raw/master/images/where_to_find_the_metascope.png)
In this case the ```meta_scope``` is ```ent_dataservices_sdk```. If you have created an integration that is bound to multiple Adobe solutions, you will see multiple entries with different ```meta_scope``` values defined. In this case, add all the meta_scopes to the ```meta_scope```, separated by a comma (```,```). For example ```ent_dataservices_sdk,ent_reactor_sdk```.

After configuring your template, it will look like this:
![Imported Postman Collection](https://github.com/ktukker/adobe.io-jwt-postman/raw/master/images/postman_environment_example.png)

Note: The ```secret``` variable contains the full text of the private key that you generated for the selected integration. Copy and past it into the field including the header (```-----BEGIN RSA PRIVATE KEY------```) and footer.


## Bootstrapping the authentication process
To be able to authenticate, you need to load the Crypto JavaScript library. This Postman script uses the ["RSA-Sign JavaScript Library"](https://github.com/kjur/jsrsasign). Due to limitations in the Postman sandbox, it needs to be loaded in a Global environment variable only once.

* Click on "INIT: Load Crypto Library for RS256" in the left column of Postman (under the Adobe I/O Collection).
* The pre-configured call will appear
* Click "Send"

This will load the JavaScript and store it in an internal variable for future use. When you shutdown Postman and start it up again, the script does not have to be run again as the internal variable still holds the value.
![Imported Postman Collection](https://github.com/ktukker/adobe.io-jwt-postman/raw/master/images/postman_load_crypto_library.png)

## Executing JWT authentication
With the Crypto JavaScript Library in place, you can now execute the real JWT authentication flow. Make sure your environment is selected in the top right of the screen.

* Click on "IMS: JWT Generate + Auth". This will generate the authentication code using the Crypto Library and call the Adobe Identity Server (IMS) to authenticate.

The Postman call will generate a bearer token and automatically store it in the selected environment as `access_token`.

As a next step, you can do a call to the Adobe IMS ```/profile``` endpoint. This will provide you additional details of the Integration you created and have authenticated with.

In my case, I can now successfully call the Adobe Cloud Platform - Data Services APIs. For example access the Data Catalog to get a list of registered datasets. (Note this is only available if your Organization is provisioned for the "Data Services" solution). 

If you are using this in combination with another Adobe Solution that is using Service-to-Service authentication, make sure that you have configured the right `meta_scope` for that solution.

## How it works
So, what is going on behind the scenes? This Postman script is using a JavaScript based Crypto library (['jsrsasign'](http://kjur.github.io/jsrsasign/)) to use the "RS256" Encryption library. Normally, this library runs in the context of a browser sandbox, but in this case, we want it to use in Postman. I used the method provide [here](https://github.com/kjur/jsrsasign/issues/199) to load it into Postman. This is done using a 'Tests' script which is executed after the call has been completed:

```
postman.setGlobalVariable("jsrsasign-js", responseBody);
```

Once the library is ready for use, it can be used in the actual JWT generation process. In a 'pre-request Script', the JavaScript library is loaded using the ```eval``` command 

```
var navigator = {}; //fake a navigator object for the lib
var window = {}; //fake a window object for the lib
eval(postman.getGlobalVariable("jsrsasign-js")); //import jsrsasign lib
```

Based on the environment variables for the current environment, the JWT payload is constructed:

```
var data = {
	"exp": Math.round(87000 + Date.now()/1000),
	"iss": postman.getEnvironmentVariable("IMSOrg"),
	"sub": postman.getEnvironmentVariable("techacct"),
	"aud": "https://ims-na1.adobelogin.com/c/"+postman.getEnvironmentVariable("clientID")
};

var meta_scope = postman.getEnvironmentVariable("IMS")+"/s/"+
                 postman.getEnvironmentVariable("meta_scope");
data[meta_scope] = true;
```

This payload needs to be signed using the crypto library and the private key that is stored in the environment variable. The actual encryption code was conveniently published on the [jsrsasign wiki] (https://github.com/kjur/jsrsasign/wiki/Tutorial-for-JWT-generation).

```
var secret = postman.getEnvironmentVariable("secret");

var sHeader = JSON.stringify(header);
var sPayload = JSON.stringify(data);
var sJWT = KJUR.jws.JWS.sign("RS256", sHeader, sPayload, secret);

postman.setEnvironmentVariable("jwt_token", sJWT);
```

The resulting token is stored in the environment again and is used in the call to the authentication server (IMS). When the call returns, a 'Test' script is parsing the response and stores the `bearer` token in the environment for use in subsequent calls.

```
var data = JSON.parse(responseBody);
postman.setEnvironmentVariable("access_token", data.access_token);
```

This ```access_token``` is now used in REST calls to APIs exposed through the Adobe I/O Gateway and included in the ```Authorization``` header.


## Tips
In this article, you have been working with JWT (JSON Web Tokens). A token might seems like a random string, but some very valuable information is stored in them. Using the website [jwt.io](https://jwt.io/) you can decipher JWT tokens. I am using this regularly to inspect tokens for debugging purposes. 

Example output of a generated JWT token (payload), which shows the content that was generated as part of the Postman script:

```
{
  "exp": 1525109214,
  "iss": "<IMSORGID REMOVE>@AdobeOrg",
  "sub": "<TECHACCT ID>@techacct.adobe.com",
  "aud": "https://ims-na1.adobelogin.com/c/<CLIENTID REMOVED>",
  "https://ims-na1.adobelogin.com/s/ent_dataservices_sdk": true
}
```

You can do the same for an ```access_token``` that was returned after the JWT authentication flow.

```
Left as an exersize to the reader
```

## Conclusion
I hope you enjoyed this article and will be using Postman a lot for JWT based integration with Adobe I/O. With this script, you won't be needed a separate command line utility to generate and encrypt your JWT token or go to the Adobe I/O Console to generate one every time you need one.