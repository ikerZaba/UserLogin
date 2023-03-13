<?php

namespace UserLoginService\Tests\Dobles;

use UserLoginService\Application\SessionManager;

class SessionManagerStub implements SessionManager
{

    public function getSessions(): int
    {
        return 10;
    }

    public function login(string $userName, string $password): bool
    {
        // TODO: Implement login() method.
    }
}