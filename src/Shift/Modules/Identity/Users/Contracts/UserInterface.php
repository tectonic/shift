<?php
namespace Tectonic\Shift\Modules\Identity\Users\Contracts;

use Tectonic\Shift\Modules\Accounts\Contracts\AccountInterface;

interface UserInterface
{
    /**
     * @return integer
     */
    public function getId();

    /**
     * @return void
     */
    public function getFirstName();

    /**
     * @return void
     */
    public function getLastName();

    /**
     * @return void
     */
    public function getEmail();

    /**
     * @return void
     */
    public function getPassword();

    /**
     * Determines whether or not the current user is an owner of the account provided.
     *
     * @param AccountInterface $account
     * @return boolean
     */
    public function ownerOf(AccountInterface $account);

    /**
     * @param string $firstName
     * @return void
     */
    public function setFirstName($firstName);

    /**
     * @param string $lastName
     * @return void
     */
    public function setLastName($lastName);

    /**
     * @param string $email
     * @return void
     */
    public function setEmail($email);

    /**
     * @param string $password
     * @return void
     */
    public function setPassword($password);

    /**
     * Should create a new instance of the entity, with the first name, last name and email provided.
     *
     * @param $firstName
     * @param $lastName
     * @param $email
     * @return UserInterface
     */
    public static function add($firstName, $lastName, $email);
}
 