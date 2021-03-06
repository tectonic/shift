<?php
namespace Tectonic\Shift\Library\Composers;

use Menufy;
use Request;
use Tectonic\Shift\Library\Menu\Menu;
use Tectonic\Shift\Library\Menu\Item;

class MainMenuComposer
{
	public function compose()
    {
        $mainMenu = new Menu('main');
        $mainMenu->addChild(new Item(trans('menu.home'), route('home')));

        $configMenu =  new Menu('configuration', trans('menu.configuration'));
        $configMenu->addChild(new Item(trans('roles.titles.main'), route('roles.index')));

        $mainMenu->addChild($configMenu);
        $mainMenu->addChild(new Item(trans('users.titles.main'), route('users.index')));
        $mainMenu->addChild(new Item(trans('accounts.titles.main'), route('accounts.index')));

        Menufy::register($mainMenu);
        Menufy::activateByUrl(Request::url());
    }
}
