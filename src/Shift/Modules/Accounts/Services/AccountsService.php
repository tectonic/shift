<?php
namespace Tectonic\Shift\Modules\Accounts\Services;

use Event;
use Illuminate\Support\Facades\Auth;
use Tectonic\Shift\Modules\Accounts\Models\Account;
use Tectonic\Shift\Library\Support\ManagementService;
use Tectonic\Shift\Modules\Accounts\Validators\AccountValidation;
use Tectonic\Shift\Modules\Accounts\Contracts\AccountRepositoryInterface;

/**
 * Class AccountsService
 *
 * The accounts service provides some methods for working with accounts and eases the use of working with
 * 1 or more accounts when logged in as an authenticated consumer that has access to those accounts.
 *
 * @package Tectonic\Shift\Modules\Accounts\Services
 */
class AccountsService
{
	/**
	 * @param AccountRepositoryInterface $repository
	 */
	public function __construct(AccountRepositoryInterface $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * Find a domain that has been registered with the system.
	 * 
	 * @param $domain
	 * @return Account
	 */
	public function getAccountForDomain($domain)
	{
		return $this->repository->requireByDomain($domain);
	}

    /**
     * Get the total number of accounts setup for this installation.
     *
     * @return integer
     */
    public function totalNumberOfAccounts()
    {
        return $this->repository->getCount();
    }
}
