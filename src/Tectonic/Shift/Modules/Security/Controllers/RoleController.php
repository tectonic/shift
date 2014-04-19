<?php

namespace Tectonic\Shift\Modules\Security\Controllers;

use Tectonic\Shift\Library\BaseController;
use Tectonic\Shift\Modules\Security\Repositories\RoleRepositoryInterface;
use Tectonic\Shift\Modules\Security\Validators\RoleValidator;

class RoleController extends BaseController
{
	public function __construct(RoleRepositoryInterface $repository, RoleValidator $roleValidator)
	{
		$this->repository = $repository;
        $this->validator = $roleValidator;
	}

    public function postStore()
    {
        $role = parent::postStore();

        if (Input::get('default')) {
            $this->repository->setDefault($role);
        }

        return $role;
    }

    public function putUpdate($id)
    {
        $role = parent::putUpdate($id);

        if (Input::get('default')) {
            $this->repository->setDefault($role);
        }

        return $role;
    }
}
