<?php


namespace Venom\Security;


use Venom\Entities\User;

interface Login
{
    public function __construct(User $user);

    public function checkCredentials(): bool;

    public function login(): bool;

    public function redirect(): void;
}