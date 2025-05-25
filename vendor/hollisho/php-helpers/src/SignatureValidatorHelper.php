<?php

namespace hollisho\helpers;

class SignatureValidatorHelper
{
    /**
     * Generate a signature.
     *
     * @param array $data The request parameters array.
     * @param string $key The secret key.
     * @param string $signType The signature type (default is MD5, optional HMAC-SHA256).
     * @return string The generated signature.
     */
    public static function generateSignature(array $data, string $key, string $signType = 'MD5'): string
    {
        // 1. Sort the parameters lexicographically.
        ksort($data);

        // 2. Concatenate the parameters into a URL-encoded key-value string.
        $string = self::toUrlParams($data);

        // 3. Append the secret key to the end.
        $string .= '&key=' . $key;

        // 4. Generate the signature based on the signature type.
        if ($signType === 'HMAC-SHA256') {
            return strtoupper(hash_hmac('sha256', $string, $key));
        } else {
            return strtoupper(md5($string));
        }
    }

    /**
     * Verify the signature.
     *
     * @param array $data The request parameters array (including the 'sign' field).
     * @param string $key The secret key.
     * @param string $signType The signature type (default is MD5, optional HMAC-SHA256).
     * @return bool Whether the signature is valid.
     */
    public static function verifySignature(array $data, string $key, string $signType = 'MD5'): bool
    {
        if (!isset($data['sign'])) {
            return false;
        }

        // Get the signature from the request.
        $requestSign = $data['sign'];

        // Generate the local signature.
        $localSign = self::generateSignature($data, $key, $signType);

        // Compare the signatures.
        return $localSign === $requestSign;
    }

    /**
     * Convert the parameters array into a URL-encoded key-value string.
     *
     * @param array $data The parameters array.
     * @return string The URL-encoded key-value string.
     */
    private static function toUrlParams(array $data): string
    {
        $buff = '';
        foreach ($data as $k => $v) {
            // Ignore empty values and the 'sign' field.
            if ($v !== '' && !is_array($v) && $k != 'sign') {
                $buff .= $k . '=' . $v . '&';
            }
        }
        return rtrim($buff, '&');
    }
}