<?php

class Cookie {

    private $name;
    private $password;
    private $timeValid;

    public function __construct(string $name, string $password, int $timeValid) {
        $this->name = $name;
        $this->password = $password;
        $this->timeValid = $timeValid;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getPassword() : string {
        return $this->password;
    }

    public function getTimeValid() : string {
        return $this->timeValid;
    }


}