<?php


class StoreUser {

    private $userName = "Admin";
    private $password = "Password";

    public function getUserName(){
        return $this->userName;
    }

    public function getPassword(){
        return $this->password;
    }

    public function matchWithDB(string $userName, string $password){
        $data = $this->getDB();
        foreach($data as $userNamePassword)
        {
            if($userNamePassword[0] === $userName && $userNamePassword[1] === $password){
                return true;
            } 
        }
        return false;
    }

    private function getDB() {
        $users = file('DB.txt');
        $userNamePassword = array();
        foreach($users as $user)
        {
            array_push($userNamePassword, explode(' ', $user));
        }
        return $userNamePassword;
    }

    

    public function registerToDB($userName, $password){
        if ($this->matchWithDB($userName)) {
            return false;
        }
        file_put_contents('DB.txt', "\n", FILE_APPEND);
        $data = sprintf("%s %s ", $userName, $password);
        file_put_contents('DB.txt', $data, FILE_APPEND);
        return true;
    }
    

}