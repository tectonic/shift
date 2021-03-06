<?php
namespace Tests\Integrated\Modules\Users\Services;

use App;
use Mockery as m;
use Recaptcha;
use Tectonic\Shift\Modules\Identity\Users\Contracts\RegistrationObserverInterface;
use Tectonic\Shift\Modules\Identity\Users\Contracts\UserRepositoryInterface;
use Tectonic\Shift\Modules\Identity\Users\Services\RegistrationService;
use Tests\IntegratedTestCase;

class RegistrationServiceTest extends IntegratedTestCase
{
    private $service;

    public function init()
    {
        $this->service = App::make(RegistrationService::class);
        $this->userRepository = App::make(UserRepositoryInterface::class);
    }

    public function testSuccessfulRegistration()
    {
        $observer = m::spy(RegistrationObserverInterface::class);

        Recaptcha::shouldReceive('check')->once()->andReturn(true);

        $this->service->registerUser([
            'firstName' => 'Kirk',
            'lastName' => 'Bushell',
            'email' => 'blah@blah.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'g-recaptcha-response' => 'oiajsdlkjasdljksadf'
        ], $observer);

        $user = $this->userRepository->getAll()->first();

        // Assert
        $observer->shouldHaveReceived('onSuccess')->once();
        $this->assertNotNull($user);
        $this->assertNotNull($user->confirmationToken);
    }
}
 