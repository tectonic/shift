<?php

namespace Tectonic\Shift\Modules\Accounts\Models;

use Tectonic\Shift\Library\BaseModel;

class User extends BaseModel
{
	protected $table = 'accounts';

    protected $fillable = ['name', 'url'];
}
