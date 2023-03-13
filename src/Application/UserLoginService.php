<?php

namespace UserLoginService\Application;

use PHPUnit\Util\Exception;
use UserLoginService\Domain\User;
use UserLoginService\Infrastructure\FacebookSessionManager;

class UserLoginService
{
    private array $loggedUsers = ["admin"];
    private SessionManager $fsm;

    /**
     * @param FacebookSessionManager $fsm
     */
    public function __construct(SessionManager $fsm)
    {
        $this->fsm = $fsm;
    }


    public function manualLogin(User $user): void
    {
        $exists = $this->find($user->getUserName());

        if($exists){
            throw new Exception("User already logged in");
        }
        else{
            $this->loggedUsers[] = $user->getUserName();
        }
    }

    private function find($name): bool
    {
        for($i=0;$i<count($this->getLoggedUsers());$i++){
            if(strcmp($name,$this->loggedUsers[$i]) == 0){
                return true;
            }
        }
        return false;
    }

    public function getLoggedUsers(): array
    {
        return $this->loggedUsers;
    }

    public function getExternalSessions(): int
    {
        return $this->fsm->getSessions();
    }

    public function login(string $userName, string $passwd): string
    {
        $loginSuccess = $this->fsm->login($userName,$passwd);
        if($loginSuccess){
            return "Login correcto";
        }
        return "Login incorrecto";
    }

}