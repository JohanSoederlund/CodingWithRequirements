<?php


class StoreUser {

    private static $pathToDir = "../databaseusers/";
    private static $fileName = "users";
    private static $fileType = ".txt";

    public function registerToDB(string $userName, string $password) : bool{
        if ($this->matchUserNameWithDB($userName)) {
            return false;
        }
        $pathToFile = self::$pathToDir . self::$fileName . self::$fileType;
        file_put_contents($pathToFile, "\n", FILE_APPEND);
        $data = sprintf("%s %s ", $userName, password_hash($password, PASSWORD_DEFAULT));
        file_put_contents($pathToFile, $data, FILE_APPEND);
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
        $pathToFile = self::$pathToDir . self::$fileName . self::$fileType;
        var_dump($pathToFile);
        $users = file($pathToFile);
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