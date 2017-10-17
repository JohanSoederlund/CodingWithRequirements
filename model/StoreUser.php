<?php


class StoreUser {

    //private $userName = "Admin";
    //private $password = "Password";
/*
    public function getUserName(){
        return $this->userName;
    }

    public function getPassword(){
        return $this->password;
    }*/

    private function matchUserNameWithDB(string $userName) : bool{
        $data = $this->getUsersFromDB(true);
        foreach($data as $userNamePassword)
        {
            if($userNamePassword[0] === $userName){
                return true;
            } 
        }
        return false;
    }

    public function matchUserWithDB(string $userName, string $password) : bool{
        $data = $this->getUsersFromDB(false);
        
        foreach($data as $userNamePassword)
        {
            if($userName === $userNamePassword[0]){
                if (password_verify($password, $userNamePassword[1])) {
                    return true;
                } 
            } 
        }
        return false;
    }

    private function getUsersFromDB(bool $onlyUserName) : array{
        $users = file('DB.txt');
        $userNamePassword = array();
        foreach($users as $user)
        {
            array_push($userNamePassword, explode(' ', $user));
            if($onlyUserName){
                unset($userNamePassword[1]);
            }
        }
        return $userNamePassword;
    }

    public function registerToDB(string $userName, string $password) : bool{
        if ($this->matchUserNameWithDB($userName)) {
            return false;
        }
        file_put_contents('DB.txt', "\n", FILE_APPEND);
        $data = sprintf("%s %s ", $userName, password_hash($password, PASSWORD_DEFAULT));
        file_put_contents('DB.txt', $data, FILE_APPEND);
        return true;
    }
    

}