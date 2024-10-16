<?php

namespace Mostafa\Kitchen\Model;

use Mostafa\Kitchen\public\Model;

class User extends Model
{
    protected $tableName = 'users';

    //register new user
    public function register($data)
    {
        $this->query('INSERT INTO users (firstname, username, password,middlename,lastname) VALUES (:firstname, :username, :password,:middlename,:lastname)');
        $this->bind(':firstname', $data['firstname']);
        $this->bind(':username', $data['username']);
        $this->bind(':password', $data['password']);
        $this->bind(':middlename', $data['middlename']);
        $this->bind(':lastname', $data['lastname']);

        if ($this->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function login($username, $password){
        $this->query('SELECT * FROM users where username = :username');
        $this->bind(':username', $username);
        
        $row = $this->single();
        // var_dump($row);die();
        $hash_password = $row->password;

        if(password_verify($password, $hash_password)){
            return $row;
            // var_dump($row);die();

        }else{
            return false;
        }
    }

    public function getUserById($id){
        $this->query('SELECT * FROM users WHERE id = :id');
        $this->bind(':id', $id);

        $row = $this->single();

        return $row;
    }

    public function findByUsername($username){
        $this->query('SELECT * FROM users WHERE username = :username');
        $this->bind(':username', $username);

        $row = $this->single();

        //check the row 
        if($this->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
}
