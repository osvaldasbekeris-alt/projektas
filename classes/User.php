<?php
require_once 'Database.php';
require_once 'Encryptor.php';

class User {
    private $db;
    private $encryptor;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->encryptor = new Encryptor();
    }

    public function register($username, $password) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            return false;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $user_key = bin2hex(openssl_random_pseudo_bytes(16));
        $encrypted_key = $this->encryptor->encrypt($user_key, $password);

        $stmt = $this->db->prepare("INSERT INTO users (username, password_hash, encrypted_key) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $password_hash, $encrypted_key]);
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    public function savePassword($user_id, $title, $plain_password, $user_key) {
        $encrypted_password = $this->encryptor->encrypt($plain_password, $user_key);
        $stmt = $this->db->prepare("INSERT INTO passwords (user_id, title, encrypted_password) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $title, $encrypted_password]);
    }

    public function getPasswords($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM passwords WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function deletePassword($id, $user_id) {
        $stmt = $this->db->prepare("DELETE FROM passwords WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $user_id]);
    }

    public function changePassword($user_id, $old_password, $new_password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($old_password, $user['password_hash'])) {
            return false;
        }

        $old_decrypted_key = $this->encryptor->decrypt($user['encrypted_key'], $old_password);
        $new_encrypted_key = $this->encryptor->encrypt($old_decrypted_key, $new_password);
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("UPDATE users SET password_hash = ?, encrypted_key = ? WHERE id = ?");
        return $stmt->execute([$new_hash, $new_encrypted_key, $user_id]);
    }
}