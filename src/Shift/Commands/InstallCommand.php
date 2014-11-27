<?php
namespace Tectonic\Shift\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'shift:install';

    /**
     * @var string
     */
    protected $description = 'Install Shift 2';

    /**
     * Fire the command, running through the following steps:
     *
     *   1. Install the migrations table
     *   2. Migrate the laravel-localisations package
     *   3. Migrate the shift package
     *   4. Publish any and all assets
     */
    public function fire()
    {
        $this->call('migrate:install');
        $this->call('migrate', array('--package' => 'tectonic/laravel-localisation'));
        $this->call('migrate', array('--package' => 'tectonic/shift'));
        $this->call('asset:publish');
        
        $this->info('Shift installed.');
    }
}
