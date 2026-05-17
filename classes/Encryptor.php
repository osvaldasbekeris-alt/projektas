<?php
class Encryptor {
    private $cipher = "aes-256-cbc";

    public function encrypt($data, $key) {
        $secret_key = hash('sha256', $key, true);
        $iv_length = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($iv_length);
        $encrypted = openssl_encrypt($data, $this->cipher, $secret_key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public function decrypt($data, $key) {
        $secret_key = hash('sha256', $key, true);
        $data = base64_decode($data);
        $iv_length = openssl_cipher_iv_length($this->cipher);
        $iv = substr($data, 0, $iv_length);
        $encrypted = substr($data, $iv_length);
        return openssl_decrypt($encrypted, $this->cipher, $secret_key, 0, $iv);
    }
}