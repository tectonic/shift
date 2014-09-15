<?php

namespace Tectonic\Shift\Modules\Security\Entities;

use Doctrine\ORM\Mapping as ORM;
use Mitch\LaravelDoctrine\Traits\SoftDeletes;
use Mitch\LaravelDoctrine\Traits\Timestamps;
use Tectonic\Shift\Library\Support\Database\Doctrine\Entity;
use Tectonic\Shift\Modules\Accounts\Entities\Accountable;

/**
 * Class Role
 *
 * Defines the role(s) a user may be assigned to as part of the system. Each role has a number
 * of rules that let the application know the resources that a user has access to. Roles are
 * an extremely important part of Shift. Users need to know what they're doing when modifying
 * role permissions.
 *
 * @ORM\Entity(repositoryClass="Tectonic\Shift\Modules\Security\Repositories\DoctrineRoleRepository")
 * @ORM\Table(name="roles")
 */
class Role extends Entity
{
    use Accountable;
    use Timestamps;
    use SoftDeletes;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $access;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(name="`default`", type="boolean", options={"`default`"=0})
     */
    private $default;

    /**
     * @ORM\OneToMany(targetEntity="Tectonic\Shift\Modules\Security\Entities\Permission", mappedBy="roleId")
     */
    private $permissions;

    /**
     * @ORM\ManyToMany(targetEntity="Tectonic\Shift\Modules\Users\Entities\User", mappedBy="userId")
     */
    private $users;
}

