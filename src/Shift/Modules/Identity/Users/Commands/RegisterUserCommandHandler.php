<?php
namespace Tectonic\Shift\Modules\Identity\Users\Commands;

use CurrentAccount;
use Tectonic\Application\Commanding\CommandHandlerInterface;
use Tectonic\Application\Eventing\EventDispatcher;
use Tectonic\Shift\Modules\Accounts\Models\Account;
use Tectonic\Shift\Modules\Accounts\Services\CurrentAccountService;
use Tectonic\Shift\Modules\Identity\Users\Contracts\UserRepositoryInterface;
use Tectonic\Shift\Modules\Identity\Users\Models\User;

class RegisterUserCommandHandler implements CommandHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        EventDispatcher $eventDispatcher
    )
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Handle the command.
     *
     * @param $command
     *
     * @return \Tectonic\Shift\Modules\Identity\Users\Models\User
     */
    public function handle($command)
    {
        $user = User::register($command->firstName, $command->lastName, $command->email, $command->password);

        $this->userRepository->save($user);

        $account = CurrentAccount::get();
        $account->addUser($user);

        $this->eventDispatcher->dispatch($user->releaseEvents());

        return $user;
    }
}
