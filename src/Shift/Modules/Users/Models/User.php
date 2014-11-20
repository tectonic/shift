<?php
namespace Tectonic\Shift\Modules\Users\Models;

use Illuminate\Auth\UserInterface as AuthUserInterface;
use Tectonic\Application\Eventing\EventGenerator;
use Tectonic\Shift\Modules\Accounts\Contracts\AccountInterface;
use Tectonic\Shift\Modules\Accounts\Models\Account;
use Tectonic\Shift\Modules\Users\Contracts\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Tectonic\Shift\Library\Support\Database\Eloquent\Model;

class User extends Model implements AuthUserInterface, RemindableInterface
{
    use EventGenerator;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * Fillable attributes for the user model.
     *
     * @var array
     */
    public $fillable = ['firstName', 'lastName', 'email', 'password'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Returns the accounts that the user owns.
     *
     * @return QueryBuilder
     */
    public function ownedAccounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Should create a new instance of the entity, with the first name, last name and email provided.
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @return User
     */
    public static function add($firstName, $lastName, $email, $password)
    {
        $user = static::create(compact('firstName', 'lastName', 'email', 'password'));

        $user->raise(new UserWasAdded($user));

        return $user;
    }

    /**
     * When installing shift, this is a special use-case.
     *
     * @param string $email
     * @param string $password
     * @return User
     */
    public static function install($email, $password)
    {
        $user = new static;

        $user->firstName = 'Super';
        $user->lastName = 'Admin';
        $user->email = $email;
        $user->lastName = $password;

        $user->raise(new AdminUserWasCreated($user));

        return $user;
    }

    /**
     * Determine whether or not the user is an owner of the provided account.
     *
     * @param Account $account
     */
    public function ownerOf(Account $account)
    {
        $ownedAccountIds = $this->ownedAccounts->lists('id');

        return in_array($account->getId(), $ownedAccountIds);
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    /**
     * Password attribute mutator
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes["password"] = \Hash::make($value);
    }

}
