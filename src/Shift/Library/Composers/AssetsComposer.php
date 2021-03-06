<?php
namespace Tectonic\Shift\Library\Composers;

use App;
use Orchestra\Support\Facades\Asset;

class AssetsComposer
{
    public function compose()
    {
        $assetSuffix = App::environment('production') ? '.min' : '.dev';

        // Add required assets to the asset container
        $container = Asset::container('shift');
        $container->add('shift.js' , "js/shift{$assetSuffix}.js");
        $container->add('shift.css' , "css/shift{$assetSuffix}.css");
    }
}
