<?php

namespace Tectonic\Shift\Modules\Identity\Users\Services;

use Tectonic\Shift\Library\Support\ManagementService;
use Tectonic\Shift\Modules\Identity\Users\Contracts\UserRepositoryInterface;
use Tectonic\Shift\Modules\Identity\Users\Validation\UserValidation;

/**
 * Class UserManagementService
 *
 * Class to manage the normal crud operations for users.
 *
 * @package Tectonic\Shift\Modules\Identity\Users\Services
 */
class UserManagementService extends ManagementService
{
    public function __construct(UserRepositoryInterface $repository, UserValidation $validator)
    {
        $this->repository = $repository;
        $this->createValidator = $validator;
        $this->updateValidator = $validator;
    }

    /**
     * Create a new resource
     *
     * @param array $input
     * @return mixed
     */
    public function create($input)
    {
        $this->createValidator->setInput($input)->validate();

        // Remove 'password_confirmation'
        unset($input['passwordConfirmation']);

        $resource = $this->repository->getNew($input);

        $this->repository->save($resource);
        $this->notify('created', $resource);

        return $resource;
    }
}
