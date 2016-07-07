<?php

class User extends Base
{
    public function authenticate($user, $pass)
    {
        $sql = "SELECT hash
                FROM users
                WHERE user = :user";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["user" => $user]);

        if (!$result) {
            return false;
        }

        $hash = $stmt->fetch();

        return password_verify($pass, $hash['hash']);
    }
}