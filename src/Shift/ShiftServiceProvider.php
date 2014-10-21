<?php
namespace Tectonic\Shift;

use App;
use Tectonic\Shift\Library\Router;
use Tectonic\Shift\Library\ServiceProvider;

class ShiftServiceProvider extends ServiceProvider
{
    /**
     * A collection of custom aliases to register
     *
     * @var array
     */
    protected $aliases = [
        'Asset'         => 'Orchestra\Support\Facades\Asset',
        'Authority'     => 'Authority\AuthorityL4\Facades\Authority',
        'Utility'       => 'Tectonic\Shift\Library\Facades\Utility',
    ];

    /**
     * Files that require loading to bootstrap shift
     *
     * @var array
     */
    protected $filesToBoot = [
        'errors',
        'macros',
        'composers',
        'routes',
    ];

    /**
     * A collection of Shift service providers to load/register.
     *
     * @var array
     */
    protected $serviceProviders = [
        'Authority\AuthorityL4\AuthorityL4ServiceProvider',
        'Orchestra\Asset\AssetServiceProvider',
        'Eloquence\EloquenceServiceProvider',
        'Tectonic\Shift\Library\Authorization\AuthorizationServiceProvider',
        'Tectonic\Shift\Library\LibraryServiceProvider',
        'Tectonic\Shift\Modules\Accounts\AccountsServiceProvider',
        'Tectonic\Shift\Modules\Configuration\ConfigurationServiceProvider',
        'Tectonic\Shift\Modules\Fields\FieldsServiceProvider',
        'Tectonic\Shift\Modules\Localisation\LocalisationServiceProvider',
        'Tectonic\Shift\Modules\Security\SecurityServiceProvider',
        'Tectonic\Shift\Modules\Users\UsersServiceProvider',
    ];

    /**
     * Files we need to register (include)
     *
     * @var array
     */
    protected $filesToRegister = [
        'commands',
        'composers',
    ];

    /**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        parent::register();

        $this->registerRouter();
        $this->registerAuthorityConfiguration();
		$this->requireFiles($this->filesToRegister);
    }

	/**
	 * Register the various classes required to Bootstrap Shift
     *
     * @returns void
	 */
	public function boot()
	{
		$this->package('tectonic/shift');

		$this->requireFiles($this->filesToBoot);
	}

	/**
	 * Sets up the configuration required by Authority when it gets loaded.
     *
     * @returns void
	 */
	public function registerAuthorityConfiguration()
	{
		$this->app['config']->set('authority-l4::initialize', function($authority) {
			$user = $authority->getCurrentUser();
		});
	}

	/**
	 * Helper method for requiring boot files. These are files that generally have some basic configuration,
	 * routes, global macros, or Laravel 4 commands that need to be registered.etc.
	 *
	 * @param array $files
     * @returns void
	 */
	public function requireFiles(array $files)
	{
        foreach($files as $file) {
            require __DIR__.'/../../boot/'.$file.'.php';
        }
	}

    /**
     * Register the router instance. This completely overwrites the one registered by Laravel.
     *
     * @return void
     */
    protected function registerRouter()
    {
        $this->app['router'] = $this->app->share(function($app)
        {
            return new Router($app['events'], $app);
        });
    }
}
