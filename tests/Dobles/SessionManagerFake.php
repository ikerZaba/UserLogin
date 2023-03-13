<?php

namespace UserLoginService\Tests\Dobles;

use UserLoginService\Application\SessionManager;

class SessionManagerFake implements SessionManager
{
    private string $stubUserName = "Iker";
    private string $stubPaswd = "aaaaa";

    public function getSessions(): int
    {
        // TODO: Implement getSessions() method.
    }

    public function login(string $userName, string $password): bool
    {
        if ($this->stubUserName == $userName && $this->stubPaswd == $password){
            return true;
        }
        return false;
    }
}