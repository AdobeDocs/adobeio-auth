var request = require("request");
var jwt = require("jsonwebtoken");
var _config = require("./config.js");

generateToken(_config, function(err, token) {
  if (err)
  {
    console.log("Error occured: " + err);
  }
  else
  {
    console.log("Access token generated: " + token);
  }
});

function generateToken(_config, callback) {
  var api_key = _config.credentials.api_key;
  var technical_account_id = _config.credentials.technical_account_id;
  var org_id = _config.credentials.org_id;
  var client_secret = _config.credentials.client_secret;
  var private_key = _config.credentials.private_key;
  var meta_scopes = _config.credentials.meta_scopes.split(",");
  var access_token = "";

  var aud = "https://ims-na1.adobelogin.com/c/" + api_key;

  var jwtPayload = {
    exp: Math.round(87000 + Date.now() / 1000),
    iss: org_id,
    sub: technical_account_id,
    aud: aud
  };

  for (var i = 0; i < meta_scopes.length; i++) {
    if (meta_scopes[i].indexOf("https") > -1) {
      jwtPayload[meta_scopes[i]] = true;
    } else {
      jwtPayload["https://ims-na1.adobelogin.com/s/" + meta_scopes[i]] = true;
    }
  }
  jwt.sign(
    jwtPayload,
    private_key,
    {
      algorithm: "RS256"
    },
    function(err, token) {
      if (err) callback(err, undefined);

      console.log("JWT Token generated: " + token);
      var accessTokenOptions = {
        uri: "https://ims-na1.adobelogin.com/ims/exchange/jwt/",
        headers: {
          "content-type": "multipart/form-data",
          "cache-control": "no-cache"
        },
        formData: {
          client_id: api_key,
          client_secret: client_secret,
          jwt_token: token
        }
      };

      console.log(
        "Trying to get an access token using request: " +
          JSON.stringify(accessTokenOptions)
      );

      request.post(accessTokenOptions, function(err, res, body) {
        if (err) {
          console.log("Error:" + err);
          callback(err, undefiend);
        }

        if (JSON.parse(body).access_token) {
          access_token = JSON.parse(body).access_token;
          callback(undefined, access_token);
        }
      });
    }
  );
}
