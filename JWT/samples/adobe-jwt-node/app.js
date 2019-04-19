const auth=require("@adobe/jwt-auth");
const _config=require("./config.js");
let options=_config.credentials;
auth(options).then(res => console.log(res));

