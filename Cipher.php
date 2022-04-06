<?php

/**
 *
 * Simple cipher class
 *
 * @author Jakub Zasański <jakub.zasanski.dev@gmail.com>
 * @copyright 2022 Jakub Zasański
 *
 * @license MIT License
 * @license https://opensource.org/licenses/MIT MIT License
 *
 * @version 1.0.0
 */


class Cipher
{
    private string $privateKey;
    private string $hashAlgo = 'sha3-512';
    private string $encryptMethod = 'aes-256-cbc-hmac-sha256';
    private string $encryptHashAlgo = 'sha3-256';
    private int $IvLength = 16;

    public function __construct(string $private_key = null)
    {
        if (!empty($private_key)) {
            $this->privateKey = $private_key;
        }
    }

    /*
     * Simple encrypt string
     * @param string
     * @return string|false
     */
    public function Encrypt(string $string = null): string|false
    {
        if (!empty($string)) {
            $aes_256_key = hash($this->encryptHashAlgo, $this->privateKey, true);

            $iv = openssl_random_pseudo_bytes($this->IvLength);

            $cipher_text = openssl_encrypt($string, $this->encryptMethod, $aes_256_key, OPENSSL_RAW_DATA, $iv);

            $hash = hash_hmac($this->encryptHashAlgo, $cipher_text . $iv, $aes_256_key, true);

            return base64_encode($iv . $hash . $cipher_text);
        }

        return false;
    }

    /*
     * Simple decrypt string
     * @param string
     * @return string|false
     */
    public function Decrypt(string $string = null): string|false
    {
        if (!empty($string)) {
            $string = base64_decode($string);

            $iv = substr($string, 0, $this->IvLength);

            $hash = substr($string, $this->IvLength, $this->IvLength * 2);

            $cipher_text = substr($string, $this->IvLength * 3);

            $aes_256_key = hash($this->encryptHashAlgo, $this->privateKey, true);

            if (!hash_equals(hash_hmac($this->encryptHashAlgo, $cipher_text . $iv, $aes_256_key, true), $hash)) {
                return false;
            } else {
                return openssl_decrypt($cipher_text, $this->encryptMethod, $aes_256_key, OPENSSL_RAW_DATA, $iv);
            }
        }

        return false;
    }

    /*
     * Simple hash string
     * @param string
     * @return string|false
     */
    public function HashString(string $string = null, bool $blackout = false): string|false
    {
        if (!empty($string)) {
            if ($blackout and $this->privateKey) {
                $part1 = $part2 = '';

                for ($j = 0; $j < strlen($string); $j++) {
                    $chr1 = substr($string, $j, 1);
                    $chr2 = substr($this->privateKey, ($j % strlen($this->privateKey)) - 1, 1);
                    $part1 .= chr(ord($chr1) - ord($chr2));
                    $part2 .= chr(ord($chr1) + ord($chr2));
                }

                $result = $part2 . hash($this->hashAlgo, $part1);
            } else {
                $result = $string;
            }

            return hash($this->hashAlgo, $result);
        }

        return false;
    }

    /*
    * Simple hash string
    * @param string
    * @return string|false
    */
    public function HashPassword(string $string = null): string|false
    {
        if (!empty($string)) {
            return $this->Encrypt(password_hash($this->HashString($string, true), '2y', ['cost' => 10]));
        }

        return false;
    }

    /*
     * Simple verify hash string
     * @param string
     * @return bool
     */
    public function VerifyPassword(string $string = null, string $hash = null): bool
    {
        if (!empty($string) and !empty($hash) and password_verify($this->HashString($string, true), $this->Decrypt($hash))) {
            return true;
        } else {
            return false;
        }
    }
}

// EOF
