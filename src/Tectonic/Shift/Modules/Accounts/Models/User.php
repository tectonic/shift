<?php

namespace Tectonic\Shift\Modules\Accounts\Models;

use Tectonic\Shift\Library\Support\BaseModel;

class User extends BaseModel
{
	protected $table = 'users';

    protected $fillable = ['name'];
}
