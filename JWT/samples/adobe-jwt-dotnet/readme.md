
# Steps

1. create the certificate and private key using openssl

```$ openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:2048 -keyout private.key -out certificate_pub.crt```

2. Upload the certificate_pub.crt in Adobe IO Console-> Your Integration-> Public keys

3. convert private key and certificate to pfx format

```$ openssl pkcs12 -export -out mycert.pfx -inkey private.key -in certificate_pub.crt```

4. Edit the program.cs file and add the properties from your Adobe Developer Console integration.


