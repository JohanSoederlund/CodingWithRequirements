<?php


class StoreUser {

    private static $pathToDB = "../DB/users.txt";
    private static $fileType = ".txt";
    

    public function registerToDB(string $userName, string $password) : bool{
        if ($this->matchUserNameWithDB($userName)) {
            return false;
        }
        file_put_contents("../DB/users.txt", "\n", FILE_APPEND);
        $data = sprintf("%s %s ", $userName, password_hash($password, PASSWORD_DEFAULT));
        file_put_contents("../DB/users.txt", $data, FILE_APPEND);
        return true;
    }

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
        $users = file("../DB/users.txt");
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

}