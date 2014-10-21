<?php
namespace Tectonic\Shift\Modules\Accounts\Repositories;

use Tectonic\Shift\Modules\Accounts\AccountNotFoundException;
use Tectonic\Shift\Modules\Accounts\Contracts\AccountInterface;
use Tectonic\Shift\Modules\Accounts\Contracts\AccountRepositoryInterface;
use Tectonic\Shift\Modules\Accounts\Models\Account;
use Tectonic\Shift\Library\Support\Database\Eloquent\Repository;
use Tectonic\Shift\Modules\Users\Contracts\UserInterface;

class EloquentAccountRepository extends Repository implements AccountRepositoryInterface
{
	/**
	 * Accounts are the top-level root domain of the entire system. Therefore, they are removed
	 * from the default account restriction for querying.
	 *
	 * @var bool
	 */
	public $restrictByAccount = false;

    /**
     * Make sure we assign the rqeuired model.
     *
     * @param Account $model
     */
    public function __construct(Account $model)
    {
        $this->model = $model;
    }

    /**
     * Creates and returns a new account instance.
     *
     * @param array $data
     * @return Account
     */
    public function getNew(array $data = [])
    {
        return Account::add($data['name']);
    }

	/**
	 * Require an account based on the domain that has been provided. If no account is found,
	 * an AccountNotFoundException is thrown.rsi
	 *
	 * @param $account
     * @return array
	 * @throws AccountNotFoundException
	 */
	public function requireByDomain($account)
    {
		$account = $this->getByDomain($account);

	    if (!$account) {
			throw new AccountNotFoundException("An account for domain [$account] could not be found.");
	    }

	    return $account;
    }

	/**
	 * Searches for an account based on the domain provided.
	 *
	 * @param $domain
	 * @return mixed
	 */
	public function getByDomain($domain)
	{
		return $this->getQuery()->whereHas('domains', function($query) use ($domain) {
            $query->whereDomain($domain);
        })->first();
	}

    /**
     * Retrieves the total count for the number of accounts added to the system (both deleted and not).
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->getQuery()->withTrashed()->count();
    }

    /**
     * @param AccountInterface $account
     * @param UserInterface $user
     * @return mixed
     */
    public function addUser(AccountInterface $account, UserInterface $user)
    {
        return $account->users()->attach($user->getId());
    }
}
