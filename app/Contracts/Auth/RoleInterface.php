<?php
/**
 * User roles.
 */

namespace App\Contracts\Auth;


interface RoleInterface
{
    const ADMIN = 1;

    const CONTENT_MANAGER = 2;

    const USER_MANAGER = 3;

    const STOREKEEPER = 4;

    const SERVICEMAN = 5;
}
