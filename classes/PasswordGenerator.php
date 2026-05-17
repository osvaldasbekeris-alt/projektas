<?php
class PasswordGenerator {
    private $length;
    private $lowercase;
    private $uppercase;
    private $numbers;
    private $special;

    public function __construct($length, $lowercase, $uppercase, $numbers, $special) {
        $this->length = $length;
        $this->lowercase = $lowercase;
        $this->uppercase = $uppercase;
        $this->numbers = $numbers;
        $this->special = $special;
    }

    public function generate() {
        $lowerChars = 'abcdefghijklmnopqrstuvwxyz';
        $upperChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numChars = '0123456789';
        $specChars = '!@#$%^&*()-_=+[]{}|;:,.<>?';

        $password = '';

        for ($i = 0; $i < $this->lowercase; $i++) {
            $password .= $lowerChars[rand(0, strlen($lowerChars) - 1)];
        }
        for ($i = 0; $i < $this->uppercase; $i++) {
            $password .= $upperChars[rand(0, strlen($upperChars) - 1)];
        }
        for ($i = 0; $i < $this->numbers; $i++) {
            $password .= $numChars[rand(0, strlen($numChars) - 1)];
        }
        for ($i = 0; $i < $this->special; $i++) {
            $password .= $specChars[rand(0, strlen($specChars) - 1)];
        }

        return str_shuffle($password);
    }
}