<?php

class User extends Model {
    protected $table = 'users';
    
    public function verifyUser($username, $password) {
        // search for the user in database

        $sql = "SELECT * FROM {$this->table} WHERE username = ? LIMIT 1";
        $stmt = $this->query($sql, [$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // verify the hashed pass
        if ($user && password_verify($password, $user['password'])) {
            return $user; // success login 
        }
        return false; // filed login
    }
}