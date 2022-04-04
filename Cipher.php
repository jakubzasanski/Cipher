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
    # Individual user crypt key storage in database
    private $salt = 'example-salt-key';

    # Global app crypt key storage in app
    private $pepper = 'example-pepper-key';


    public function __construct(string $salt = null, string $pepper = null)
    {
        if (!empty($salt)) {
            $this->salt = $salt;
        }

        if (!empty($pepper)) {
            $this->pepper = $pepper;
        }
    }

    /*
     * Simple encrypt string
     * @param string
     * @return string
     */
    public function Encrypt(string $string = null)
    {
        if (!empty($string)) {
            $_return = $string;
            //TODO::AES-256

            return $_return;
        }

        return false;
    }

    /*
     * Simple decrypt string
     * @param string
     * @return string
     */
    public function Decrypt(string $string = null)
    {
        if (!empty($string)) {
            $_return = $string;
            //TODO::AES-256

            return $_return;
        }

        return false;
    }

    /*
     * Simple hash string
     * @param string
     * @return string
     */
    public function HashString(string $string = null, bool $blackout = true)
    {
        if (!empty($string)) {
            if ($blackout) {
                $result = '';
                for ($i = 0; $i < strlen($string); $i++) {
                    $result .= substr($string, $i, 1);
                    $result .= substr($this->salt, ($i % strlen($this->salt)) - 1, 1);
                }
            } else {
                $result = $string;
            }

            return hash('sha512', $result);
        }

        return false;
    }

    /*
    * Simple hash string
    * @param string
    * @return string
    */
    public function HashPassword(string $string = null)
    {
        if (!empty($string)) {
            return $this->Encrypt(password_hash($this->HashString($string), '2y', ['cost' => 10]));
        }

        return false;
    }

    /*
     * Simple verify hash string
     * @param string
     * @return string
     */
    public function VerifyPassword(string $string = null, string $hash = null)
    {
        if (!empty($string) and !empty($hash) and password_verify($this->HashString($string), $this->Decrypt($hash))) {
            return true;
        }

        return false;
    }

    /*
    * Generate Encryptor salt
    */
    public function GenSalt()
    {
        $this->salt = bin2hex(random_bytes(32));
    }

    /*
    * Set Encryptor salt
    */
    public function SetSalt(string $salt = null)
    {
        $this->salt = $salt;
    }

    /*
     * Get Encryptor salt
     * @return string
     */
    public function GetSalt()
    {
        return $this->salt;
    }
}

// EOF
