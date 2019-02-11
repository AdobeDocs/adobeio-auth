# create the certificate and private key using openssl
```$ openssl req -nodes -text -x509 -newkey rsa:2048 -keyout secret.pem -out certificate.pem -days 356```
- Upload the certificate.pem in Adobe IO Console-> Your Integration-> Public keys
- convert private key to DER format
```$ openssl pkcs8 -topk8 -inform PEM -outform DER -in secret.pem  -nocrypt > secret.key```
- Secret key as byte array. Secret key file should be in DER encoded format.
