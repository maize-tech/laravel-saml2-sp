<?php

namespace Maize\Saml2Sp\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Maize\Saml2Sp\Support\Config;

class LogoutUser
{
    public function __invoke(): Authenticatable
    {
        $user = $this->getUser();

        $this->logoutUser($user);

        return $user;
    }

    protected function getUser(): Authenticatable
    {
        return Auth::guard(
            $this->getAuthGuard()
        )->user();
    }

    protected function logoutUser(Authenticatable $user): void
    {
        Auth::guard(
            $this->getAuthGuard()
        )->logout();
    }

    protected function getAuthGuard(): ?string
    {
        return Config::getAuthGuard();
    }
}
