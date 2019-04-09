using System;
using System.Security.Cryptography.X509Certificates;
using Jose;
using RestSharp;
using System.Collections.Generic;

//NuGet packages: Jose and RestSharp

namespace adobe_jwt_dotnet
{
    class Program
    {
        static void Main(string[] args)
        {
            //Copy these details from console.adobe.io integration
            const string CLIENT_ID = "YOUR API KEY (CLIENT ID)";        //e.g. ......328adcb8062d
            const string CLIENT_SECRET = "YOUR CLIENT SECRET";          //e.g. ......-ad95-70e50b6b4ea3
            const string TECH_ACC_ID = "YOUR TECHNICAL ACCOUNT ID";     //e.g. ......0A495C2A@techacct.adobe.com
            const string ORG_ID = "YOUR ORGANIZATION ID";               //e.g. ......0A495D09@AdobeOrg
            const string METASCOPES = "YOUR METASCOPES IN COMMA SEPARATED FORMAT"; //e.g. "https://ims-na1.adobelogin.com/s/ent_reactor_developer_sdk,https://ims-na1.adobelogin.com/s/ent_reactor_admin_sdk"


            Dictionary<object, object> test = new Dictionary<object, object>();
            test.Add("exp", DateTimeOffset.Now.ToUnixTimeSeconds()+600);
            test.Add("iss", ORG_ID);
            test.Add("sub", TECH_ACC_ID);
            test.Add("aud", "https://ims-na1.adobelogin.com/c/"+CLIENT_ID);
            string[] scopes = METASCOPES.Split(',');

            foreach(string scope in scopes)
            {
                test.Add(scope, true); 
            }

            //You must have generated a certificate using below command and uploaded in the console.adobe.io integration
            //openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:2048 -keyout private.key -out certificate_pub.crt

            //Create a pfx file using the private key and public certificate generated from the above step
            //openssl pkcs12 -export -out mycert.pfx -inkey private.key -in certificate_pub.crt
            //Enter export password: password

            try
            {
                X509Certificate2 cert = new X509Certificate2("/path/to/mycert.pfx", "password");

                string token = Jose.JWT.Encode(test, cert.GetRSAPrivateKey(), JwsAlgorithm.RS256);

                Console.WriteLine(token); //Intermediate JWT Token

                var client = new RestClient("https://ims-na1.adobelogin.com/ims/exchange/jwt/");

                var request = new RestRequest(Method.POST);
                request.AddHeader("cache-control", "no-cache");
                request.AddHeader("content-type", "multipart/form-data; boundary=----boundary");
                request.AddParameter("multipart/form-data; boundary=----boundary",
                    "------boundary\r\nContent-Disposition: form-data; name=\"client_id\"\r\n\r\n" + CLIENT_ID + 
                    "\r\n------boundary\r\nContent-Disposition: form-data; name=\"client_secret\"\r\n\r\n" + CLIENT_SECRET + 
                    "\r\n------boundary\r\nContent-Disposition: form-data; name=\"jwt_token\"\r\n\r\n" + token + 
                    "\r\n------boundary--", ParameterType.RequestBody);


                IRestResponse response = client.Execute(request);

                Console.WriteLine(response.Content);
            }
            catch(Exception e)
            {
                Console.WriteLine("An error has occured: "+e.Message);
            }


        }
    }
}