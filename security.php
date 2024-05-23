<?php
ini_set('display_errors', 0);
$ENCRYPTION_KEY = getenv('ENCRYPTION_KEY');

function encryptPassword($password) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($password, 'aes-256-cbc', $ENCRYPTION_KEY, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decryptPassword($encrypted_password) {
    list($encrypted_data, $iv) = explode('::', base64_decode($encrypted_password), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $ENCRYPTION_KEY, 0, $iv);
}
