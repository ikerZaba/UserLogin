<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use PHPUnit\Framework\TestCase;
use UserLoginService\Application\UserLoginService;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;
use UserLoginService\Tests\Dobles\SessionManagerDummy;
use UserLoginService\Tests\Dobles\SessionManagerStub;
use UserLoginService\Tests\Dobles\SessionManagerFake;

final class UserLoginServiceTest extends TestCase
{

    /**
     * @test
     */
    public function userIsLoggedIn()
    {
        $userLoginService = new UserLoginService(new SessionManagerDummy());

        $user = new User("admin");

        $this->expectExceptionMessage("User already logged in");

        $userLoginService->manualLogin($user);
    }

    /**
     * @test
     */
    public function userIsNotLoggedIn()
    {
        $userLoginService = new UserLoginService(new SessionManagerDummy());
        $user = new User("Iker");

        $userLoginService->manualLogin($user);

        $this->expectExceptionMessage("User already logged in");

        $userLoginService->manualLogin($user);
    }

    /**
     * @test
     */
    public function getManuallyLoggedUsers()
    {
        $userLoginService = new UserLoginService(new SessionManagerDummy());

        $list = $userLoginService->getLoggedUsers();

        $this->assertEquals(1,$this->count($list));
    }

    /**
     * @test
     */
    public function getExternalServiceSessionQuantity()
    {
        $userLoginService = new UserLoginService(new SessionManagerStub());

        $quantity = $userLoginService->getExternalSessions();

        $this->assertEquals(10,$quantity);
    }

    /**
     * @test
     */
    public function FacebookLogin()
    {
        $userLoginService = new UserLoginService(new SessionManagerFake());
        $user = new User("Iker");

        $success = $userLoginService->login($user->getUserName(),"aaaaa");

        $this->assertEquals("Login correcto",$success);
    }
}
