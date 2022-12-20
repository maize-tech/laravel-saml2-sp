<?php

namespace Maize\Saml2Sp\Actions;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Maize\Saml2Sp\SamlUserData;
use Maize\Saml2Sp\Support\Config;

class AuthenticateUser
{
    /**
     * @throws Exception
     */
    public function __invoke(SamlUserData $userData): Authenticatable
    {
        $user = $this->getUser($userData);

        $this->authenticateUser($user);

        return $user;
    }

    /**
     * @throws Exception
     */
    protected function getUser(SamlUserData $userData): Authenticatable
    {
        return $this->getUserModel()
            ->query()
            ->where([
                'email' => $userData->nameId,
            ])
            ->firstOrFail();
    }

    protected function authenticateUser(Authenticatable $user): void
    {
        Auth::guard(
            $this->getAuthGuard()
        )->login($user);
    }

    /**
     * @throws Exception
     */
    protected function getUserModel(): Authenticatable
    {
        return Config::getUserModel();
    }

    protected function getAuthGuard(): ?string
    {
        return Config::getAuthGuard();
    }
}
