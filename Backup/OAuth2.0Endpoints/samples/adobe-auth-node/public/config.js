/* Replace "YOUR_ADOBE_API_KEY" with your Api key 
and "YOUR_ADOBE_API_SECRET" with your API Secret */

const adobeApiKey = "YOUR_ADOBE_API_KEY";
const adobeApiSecret = "YOUR_ADOBE_API_SECRET";

try {
        if (module) {
                module.exports = {
                        adobeApiKey: adobeApiKey,
                        adobeApiSecret: adobeApiSecret,
                }
        }
}
catch (err) {}
