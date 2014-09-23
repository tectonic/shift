<?php namespace Tectonic\Shift\Modules\Security\Services;

use Tectonic\Shift\Library\Support\ManagementService;
use Tectonic\Shift\Modules\Security\Repositories\RoleRepositoryInterface;
use Tectonic\Shift\Modules\Security\Validators\RoleValidation;

class RoleManagementService extends ManagementService
{
	/**
	 * @param RoleRepositoryInterface $roleRepository
	 * @param RoleValidation $validator
	 */
	public function __construct(RoleRepositoryInterface $roleRepository, RoleValidation $validator)
    {
        $this->repository = $roleRepository;
	    $this->createValidator = $validator;
	    $this->updateValidator = $validator;
    }
}
