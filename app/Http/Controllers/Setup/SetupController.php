<?php

namespace App\Http\Controllers\Setup;

use App\Contracts\Auth\RoleInterface;
use App\Models\Role;
use App\Models\User;
use App\Http\Controllers\Controller;

class SetupController extends Controller
{
    /**
     * Setup the app.
     */
    public function setup()
    {
        $this->fillLibraries();
        $this->insertRootUser();
    }

    /**
     * Fill library models.
     */
    private function fillLibraries()
    {
        Role::create(require app_path('Http/Controllers/Setup/Libraries/roles.php'));
    }

    /**
     * Create root user with all roles.
     *
     * @return void
     */
    private function insertRootUser()
    {
        // create root user
        $user = User::create([
            'name' => 'Nikeddarn',
            'email' => 'nikeddarn@gmail.com',
            'password' => bcrypt('assodance'),
        ]);

        // attach roles to root user
        $user->roles()->sync([
            RoleInterface::ADMIN,
            RoleInterface::USER_MANAGER,
            RoleInterface::VENDOR_MANAGER,
            RoleInterface::SERVICEMAN,
            RoleInterface::STOREKEEPER,
        ]);
    }
}
