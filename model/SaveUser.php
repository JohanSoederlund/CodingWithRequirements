<?php

class SaveUser {

    private static $pathToDir = "../databaseusers/";
    private static $fileName = "users";
    private static $fileType = ".txt";

    /**
	* Saves Username and hashed password in single newlines divided by a space. 
    * @param string $userName - string representation of a username
    * @param string $password - hashed password, hard to extract, validated with original string and algo-settings
    * @return bool returns false if this username allready exist in db.
	*/
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

    /**
	* Loops over total username db, searching for matching string. 
    * @param string $userName - string representation of a username
    * @return bool returns true if match.
	*/
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

    /**
	* Loops over total users db, searching for matching username match and password verification. 
    * @param string $userName - string representation of a username
    * @param string $password - string representation of a orginal password
    * @return bool returns true if match.
	*/
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

    /**
	* Reads from databasefile 
    * @param bool $onlyUserName - true if password should be left out
    * @return array $userNamePassword complete database, one index per user
	*/
    private function getUsersFromDB(bool $onlyUserName) : array{
        $pathToFile = self::$pathToDir . self::$fileName . self::$fileType;
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