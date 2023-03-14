<?php

declare(strict_types=1);

namespace UserLoginService\Tests\Application;

use PHPUnit\Framework\TestCase;
use UserLoginService\Application\SessionManager;
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
        $sessionmanager = \Mockery::mock(SessionManager::class);

        $userLoginService = new UserLoginService($sessionmanager);

        $user = new User("admin");

        $this->expectExceptionMessage("User already logged in");

        $userLoginService->manualLogin($user);
    }

    /**
     * @test
     */
    public function userIsNotLoggedIn()
    {
        $sessionmanager = \Mockery::mock(SessionManager::class);

        $userLoginService = new UserLoginService($sessionmanager);
        $user = new User("IkerZaba");

        $userLoginService->manualLogin($user);

        $this->expectExceptionMessage("User already logged in");

        $userLoginService->manualLogin($user);
    }

    /**
     * @test
     */
    public function getManuallyLoggedUsers()
    {
        $sessionmanager = \Mockery::mock(SessionManager::class);

        $userLoginService = new UserLoginService($sessionmanager);

        $list = $userLoginService->getLoggedUsers();

        $this->assertEquals(1,$this->count($list));
    }

    /**
     * @test
     */
    public function getExternalServiceSessionQuantity()
    {
        $sessionmanager = \Mockery::mock(SessionManager::class);
        $userLoginService = new UserLoginService($sessionmanager);

        $sessionmanager
            ->allows()
            ->getSessions()
            ->andReturn(10);

        $quantity = $userLoginService->getExternalSessions();

        $this->assertEquals(10,$quantity);
    }

    /**
     * @test
     */
    public function FacebookLogin()
    {
        $sessionmanager = \Mockery::mock(SessionManager::class);
        $userLoginService = new UserLoginService($sessionmanager);
        $user = new User("Iker");

        $sessionmanager
            ->allows()
            ->login("Iker","aaaaa")
            ->andReturnTrue();

        $success = $userLoginService->login($user->getUserName(),"aaaaa");

        $this->assertEquals("Login correcto",$success);
    }

    /**
     * @test
     */
    public function FacebookLogoutFail()
    {
        $sessionmanager = \Mockery::spy(SessionManager::class);
        $userLoginService = new UserLoginService($sessionmanager);
        $user = new User("IkerZaba");

        $sessionmanager
            ->allows()
            ->logout($user->getUserName())
            ->andReturnFalse();

        $success = $userLoginService->logout($user->getUserName());

        $this->assertEquals("User not found",$success);
    }

    /**
     * @test
     */
    public function FacebookLogoutSuccess()
    {
        $sessionmanager = \Mockery::spy(SessionManager::class);
        $userLoginService = new UserLoginService($sessionmanager);
        $user = new User("Iker");

        $sessionmanager
            ->allows()
            ->logout($user->getUserName())
            ->andReturnTrue();

        $success = $userLoginService->logout($user->getUserName());

        $sessionmanager
            ->shouldHaveReceived()
            ->logout($user->getUserName());

        $this->assertEquals("ok",$success);
    }

}
