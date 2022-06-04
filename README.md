# Cipher-PHP

Simple cipher class

![version](https://img.shields.io/github/v/tag/jakubzasanski/Cipher-PHP?label=version)
![license](https://img.shields.io/github/license/jakubzasanski/Cipher-PHP)

---

## Usage

#### Hash password to storage

```php
# Include class file
require_once "Cipher.php";

# Create Cipher object 
$cipher = new Cipher(APP_PRIV_KEY);

# Hash password
$hashed_password = $cipher->HashPassword($plaintext);
```

#### Compare password and hash

```php
$cipher = new Cipher(APP_PRIV_KEY);

if ($cipher->VerifyPassword($plaintext, $hashed_password)) {
    echo 'Success!';
} else {
    echo 'Failed!';
}
```

#### Encrypt/decrypt string

```php
$cipher = new Cipher(APP_PRIV_KEY);

$string = 'example';

$encrypted = $cipher->Encrypt($string);
$decrypted = $cipher->Decrypt($encrypted);
```

---

Like my work? Buy me a beer! 🍺

[![Donate](https://img.shields.io/badge/Donate-PayPal-blue.svg)](https://www.paypal.com/donate/?hosted_button_id=KWNT5X4DUL2AY)
