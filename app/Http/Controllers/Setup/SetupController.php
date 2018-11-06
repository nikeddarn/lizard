<?php

namespace App\Http\Controllers\Setup;

use App\Contracts\Auth\RoleInterface;
use App\Models\Badge;
use App\Models\City;
use App\Models\Role;
use App\Models\StorageDepartment;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Vendor;

class SetupController extends Controller
{
    /**
     * Setup the app.
     */
    public function setup()
    {
        $this->fillLibraries();
        $this->insertRootUser();
        $this->insertMainStorage();
        $this->insertVendors();

        return view('elements.setup.setup_complete');
    }

    /**
     * Fill library models.
     */
    private function fillLibraries()
    {
        foreach (require app_path('Http/Controllers/Setup/Libraries/roles.php') as $role) {
            Role::firstOrNew($role)->save();
        }

        foreach (require app_path('Http/Controllers/Setup/Libraries/storage_departments.php') as $department) {
            StorageDepartment::firstOrNew($department)->save();
        }

        foreach (require app_path('Http/Controllers/Setup/Libraries/product_badges.php') as $badge) {
            Badge::firstOrNew($badge)->save();
        }
    }

    /**
     * Create root user with all roles.
     *
     * @return void
     */
    private function insertRootUser()
    {
        // create root user
        $user = User::firstOrNew([
            'name' => 'Nikeddarn',
            'email' => 'nikeddarn@gmail.com',
        ]);

        $user->password = bcrypt('assodance');
        $user->save();

        // attach roles to root user
        $user->roles()->sync([
            RoleInterface::ADMIN,
            RoleInterface::USER_MANAGER,
            RoleInterface::VENDOR_MANAGER,
            RoleInterface::SERVICEMAN,
            RoleInterface::STOREKEEPER,
        ]);
    }

    private function insertMainStorage()
    {
        $city = City::firstOrNew([
            'name_ru' => 'Киев',
            'name_ua' => 'Київ',
        ]);

        $city->save();

        $storage = $city->storages()->firstOrNew([
            'name_ru' => 'Лукьяновка',
            'name_ua' => 'Лук\'янівка',
            'primary' => 1,
        ]);

        $storage->save();
    }

    private function insertVendors()
    {
        foreach (require app_path('Http/Controllers/Setup/Libraries/vendors.php') as $vendor) {
            Vendor::firstOrNew($vendor)->save();
        }
    }
}
