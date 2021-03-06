<?php
namespace Tectonic\Shift\Modules\Authentication\Contracts;

use Illuminate\Auth\UserInterface;
use Tectonic\Application\Validation\ValidationException;
use Tectonic\Shift\Modules\Authentication\Exceptions\InvalidAuthenticationCredentialsException;
use Tectonic\Shift\Modules\Authentication\Exceptions\UserAccountAssociationException;
use Tectonic\Shift\Modules\Identity\Users\Models\User;

interface AuthenticationResponderInterface
{
    /**
     * When authentication has succeeded, then the $user object belonging to the newly
     * authenticated user back and can be handled by this observer method.
     *
     * @param \Tectonic\Shift\Modules\Identity\Users\Models\User $user
     *
     * @return mixed
     */
    public function onSuccess(User $user);

    /**
     * Called when a validation exception is thrown by the command handler.
     *
     * @param ValidationException $e
     * @return mixed
     */
    public function onValidationFailure(ValidationException $e);

    /**
     * Called when a authentication exception is thrown by the command handler.
     *
     * @param InvalidAuthenticationCredentialsException $e
     * @return mixed
     */
    public function onAuthenticationFailure(InvalidAuthenticationCredentialsException $e);

    /**
     * Called when a user-account association exception is thrown by the command handler.
     *
     * @param UserAccountAssociationException $e
     * @return mixed
     */
    public function onUserAccountFailure(UserAccountAssociationException $e);
}
