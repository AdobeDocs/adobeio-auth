<?php
/**
 * Author: deepkamal.singh@adobe.com
 * Date: 03/12/19
 * Time: 16:46
 */

const IMS_HOST = 'https://ims-na1.adobelogin.com/';
const AUTH_ENDPOINT = IMS_HOST . 'ims/exchange/jwt/'; //token auth Endpoint
const AUD_ENDPOINT_PREFIX = IMS_HOST . 'c/';
const METASCOPE_ENDPOINT_PREFIX = IMS_HOST . 's/';

/**
 * Class JWTProvider
 * simple implementation
 */
class JWTProvider
{

    private const SIGN_ALGORITHM = 'RS256';

    /**
     * Converts and signs array into a JWT string.
     *
     * @param array $payload
     * @param string $key
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function encode(array $payload, string $key)
    {
        $header = ['typ' => 'JWT', 'alg' => self::SIGN_ALGORITHM];

        $headerJson = json_encode($header);
        $segments[] = $this->urlSafeB64Encode($headerJson);

        $payloadJson = json_encode($payload);
        echo "Paylod JSON:\n" . $payloadJson . "\n\n";
        $segments[] = $this->urlSafeB64Encode($payloadJson);

        //now going to use openssl_sign()
        $result = openssl_sign(implode('.', $segments),
            $signature,
            $key,
            'sha256');
        if (false === $result) {
            throw new \RuntimeException('Failed to encrypt value. ' . implode("\n", $this->getSslErrors()));
        }
        $segments[] = $this->urlSafeB64Encode($signature);

        return implode('.', $segments); //PACK THE ARRAY CONTAINING JWT

    }

    /**
     * Encode a string with URL-safe Base64.
     *
     * @param string $input The string you want encoded
     *
     * @return string
     */
    private function urlSafeB64Encode(string $input): string
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }


    /**
     * Builds request JWT.
     *
     * @param string $formattableTimeString
     * @param string $issuer
     * @param string $subject
     * @param string $audience
     * @param string $metascopes
     * @return string[]
     */
    public function buildJWTPayload($formattableTimeString, $issuer, $subject, $audience, $metascopes)
    {

        $data = [
            "exp" => strtotime($formattableTimeString),
            "iss" => $issuer,
            "sub" => $subject,
            "aud" => $audience
        ];

        if (is_array($metascopes)) {
            foreach ($metascopes as &$aMetascope) {
                $data[METASCOPE_ENDPOINT_PREFIX . $aMetascope] = true;
            }
        } else {
//            single metascope
            $data[METASCOPE_ENDPOINT_PREFIX . $metascopes] = true;
        }

        return $data;
    }

}


/** Authenticates with Adobe IO - returns Auth Response
 * @param $jwt
 * @param $client_id
 * @param $client_secret
 * @return mixed |
 */
function doAdobeIOAuth($jwt, $client_id, $client_secret)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array(
        "client_id" => $client_id,
        "client_secret" => $client_secret,
        "jwt_token" => $jwt,

    ));
    curl_setopt($curl, CURLOPT_URL, AUTH_ENDPOINT);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

/** psvm - example implementation
 *  Usage:
 *      AccessTokenProvider.php -i <client-id> -s <client-secret> -k <key-file> -u <issuer> -b <subject> -c <metascopes comma separated> -e <exp time, default 1 Day>"
 */
function psvm()
{

    $cl_options = ""
        . "i:"       // Client Id
        . "s:"       // Client Secret
        . "k:"       // Key File path
        . "u:"       // Issuer
        . "b:"       // Subject
        . "c:"       // metascopes
        . "e::";     // expires

    $show_usage = false;
    $cmdLineOpts = getopt($cl_options);

    if (isset($cmdLineOpts["i"])) {
        $client_id = $cmdLineOpts["i"];
        if (isset($cmdLineOpts["s"])) {
            $client_secret = $cmdLineOpts["s"];
            if (isset($cmdLineOpts["k"])) {
                $key_file = $cmdLineOpts["k"];
                echo "Key file is " . $key_file . "\n";
                if (is_file($key_file) && is_readable($key_file)) {
                    $fHandle = fopen($key_file, "r") or die("Unable to read the key file " . $key_file . "\n");
                    $private_key = fread($fHandle, filesize($key_file)) or die("Unable to read the key file " . $key_file . "\n");
                    fclose($fHandle);
                    if (isset($cmdLineOpts["u"])) {             //Checking issuer
                        $issuer = $cmdLineOpts["u"];
                        if (isset($cmdLineOpts["b"])) {         //checking subject
                            $subject = $cmdLineOpts["b"];
                            $audience = AUD_ENDPOINT_PREFIX . $client_id;  //preparing audience
                            if (isset($cmdLineOpts["c"])) { //checking metascopes

                                $metascopes = preg_split("/,/", $cmdLineOpts["c"]);

                                if (isset($cmdLineOpts["e"])) {
                                    $exp_time = $cmdLineOpts["e"];
                                } else {
                                    echo "exp_time not provided assuming 1 day\n";
                                    $exp_time = '1 Day';
                                }

                                $jwtInstance = new JWTProvider();

                                $payload = $jwtInstance->buildJWTPayload($exp_time,
                                    $issuer,
                                    $subject,
                                    $audience,
                                    $metascopes);

                                $jwt = $jwtInstance->encode($payload, $private_key);

                                echo "JWT Prepared:" . $jwt . "\n\n";

                                $result = doAdobeIOAuth($jwt, $client_id, $client_secret);

                                echo "Result of Auth:\n\n" . $result . "\n";
                            } else {
                                echo "Metascopes not provided\n";
                                $show_usage = true;
                            }
                        } else {
                            echo "Subject not provided\n";
                            $show_usage = true;

                        }
                    } else {
                        echo "Issuer not provided\n";
                        $show_usage = true;

                    }
                } else {
                    echo "Unable to read the key file " . $key_file . '\n';
                }
            } else {
                echo "Key file location not provided\n";
                $show_usage = true;
            }
        } else {
            echo "Client-secret not provided\n";
            $show_usage = true;
        }
    } else {
        echo "Client ID not provided\n";
        $show_usage = true;
    }

    if ($show_usage) {
        echo "Usage:\n  AccessTokenProvider.php -i <client-id> -s <client-secret> -k <key-file> -u <issuer> -b <subject> -a <audience> -c <metascopes comma separated> -e <exp time, default 1 Day>";
    }
}

psvm();
exit(1);