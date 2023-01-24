<?php

class User extends DB
{
    public $id;
    public $userName;
    public $password;
    public $email;
    public $firstName;
    public $lastName;

    public function save()
    {
        $stmt = $this -> conn -> prepare('INSERT INTO users(`username`, `email`, `password`, `first_name`, `last_name`)
                                          VALUES (:username, :email, :password, :first_name, :last_name)');
        $stmt->execute([
            'username' => $this -> userName,
            'email' => $this -> email,
            'password' => $this -> password,
            'first_name' => $this -> firstName,
            'last_name' => $this -> lastName
        ]);
        $this -> id = $this -> conn -> lastInsertId();
        return $this -> id;
    }

    public function find($id)
    {
        $stmt = $this -> conn -> prepare('SELECT * FROM users WHERE id = :id');
        $stmt -> execute(['id' => $id]);
        $user = $stmt -> fetch(PDO::FETCH_LAZY);
        if(!empty($user)) {
            $this -> id = $id;
            $this -> userName = $user -> user_name;
            $this -> email = $user -> email;
            $this -> firstName = $user -> first_name;
            $this -> lastName = $user -> last_name;
            return $this;
        }
    }

    public function checkLogin($userName, $password)
    {
        $stmt = $this -> conn -> prepare('SELECT * FROM  users
                                          WHERE (username = :username or email = :username)
                                          and password = :password');
        $stmt -> execute(['username' => $userName, 'password' => $password]);
        $user = $stmt -> fetch(PDO::FETCH_LAZY);
        if(!empty($user)) {
            $this -> id = $user -> id;
            $this -> userName = $user -> user_name;
            $this -> email = $user -> email;
            $this -> firstName = $user -> first_name;
            $this -> lastName = $user -> last_name;
            return $this;
        }
        else return false;
    }

    public function getUserName($userName)
    {
        $stmt = $this -> conn -> prepare('SELECT username FROM users WHERE username = :username');
        $stmt -> execute(['username' => $userName]);
        $user = $stmt -> fetch(PDO::FETCH_LAZY);
        if (!empty($user -> username)) {
            return $user -> username;
        } else {
            return 0;
        }
    }

    public function getEmail($email)
    {
        $stmt = $this -> conn -> prepare('SELECT email FROM users WHERE email = :email');
        $stmt -> execute(['email' => $email]);
        $user = $stmt -> fetch(PDO::FETCH_LAZY);
        if (!empty($user -> email)) {
            return $user -> email;
        } else {
            return 0;
        }
    }
}