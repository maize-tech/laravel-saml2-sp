<?php

namespace Maize\Saml2Sp\Actions;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Maize\Saml2Sp\SamlError;
use Maize\Saml2Sp\SamlUserData;
use Maize\Saml2Sp\Support\Config;

class AuthenticateUser
{
    /**
     * @throws Exception
     */
    public function __invoke(SamlUserData $userData): Authenticatable
    {
        $user = $this->resolveUser($userData);

        $this->authenticateUser($user);

        return $user;
    }

    /**
     * @throws Exception
     */
    protected function resolveUser(SamlUserData $userData): Authenticatable
    {
        $identifier = $this->getIdentifier($userData);

        /** @var Authenticatable|null $user */
        $user = $this->getUserModel()
            ->query()
            ->where($this->getIdentifierColumn(), $identifier)
            ->first();

        if (! is_null($user)) {
            return $user;
        }

        if (Config::getJitProvisioningEnabled()) {
            return $this->createUser($userData, $identifier);
        }

        throw new SamlError(
            msg: 'No user found for the given identifier.',
            code: SamlError::SAML_USER_NOT_FOUND
        );
    }

    /**
     * Resolve the value used to look up the user. When no SAML attribute is
     * configured, the nameId is used (default behaviour).
     */
    protected function getIdentifier(SamlUserData $userData): ?string
    {
        $attribute = Config::getUserIdentifierSamlAttribute();

        if (is_null($attribute)) {
            return $userData->nameId;
        }

        return $userData->getAttribute($attribute, onlyFirst: true);
    }

    /**
     * Create a new user from the SAML response (just-in-time provisioning).
     *
     * @throws Exception
     */
    protected function createUser(SamlUserData $userData, ?string $identifier): Authenticatable
    {
        $attributes = collect(Config::getJitAttributeMap())
            ->map(fn (string $samlAttribute) => $userData->getAttribute($samlAttribute, onlyFirst: true))
            ->put($this->getIdentifierColumn(), $identifier)
            ->all();

        /** @var Authenticatable */
        return $this->getUserModel()
            ->query()
            ->create($attributes);
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

    protected function getIdentifierColumn(): string
    {
        return Config::getUserIdentifierColumn();
    }

    protected function getAuthGuard(): ?string
    {
        return Config::getAuthGuard();
    }
}
