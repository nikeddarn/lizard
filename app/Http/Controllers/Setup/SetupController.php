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
use App\Support\Vendors\VendorBroker;
use Exception;

class SetupController extends Controller
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var City
     */
    private $city;

    /**
     * SetupController constructor.
     * @param User $user
     * @param City $city
     */
    public function __construct(User $user, City $city)
    {
        $this->user = $user;
        $this->city = $city;
    }

    /**
     * Setup the app.
     */
    public function setup()
    {
        $this->fillLibraries();
        $this->insertRootUser();
        $this->insertLocalStorages();
        $this->insertVendors();

        return view('elements.setup.setup_complete');
    }

    /**
     * Setup vendor's data.
     *
     * @param Vendor $vendor
     * @param VendorBroker $vendorBroker
     * @return \Illuminate\View\View
     */
    public function setupVendors(Vendor $vendor, VendorBroker $vendorBroker)
    {
        try {

            $vendor->newQuery()->get()->each(function (Vendor $vendor) use ($vendorBroker) {
                $vendorBroker->getVendorSetupManager($vendor->id)->setup();
            });

        } catch (Exception $exception) {
            return view('elements.setup.setup_complete')->withErrors([
                'message' => $exception->getMessage(),
            ]);
        }

        return view('elements.setup.setup_complete');
    }

    /**
     * Fill library models.
     */
    private function fillLibraries()
    {
        foreach (require app_path('Http/Controllers/Setup/Libraries/roles.php') as $role) {
            Role::query()->firstOrCreate($role);
        }

        foreach (require app_path('Http/Controllers/Setup/Libraries/storage_departments.php') as $department) {
            StorageDepartment::query()->firstOrCreate($department);
        }

        foreach (require app_path('Http/Controllers/Setup/Libraries/product_badges.php') as $badge) {
            Badge::query()->firstOrCreate($badge);
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
        $user = $this->user->newQuery()->firstOrNew([
            'name' => 'Nikeddarn',
            'email' => 'nikeddarn@gmail.com',
        ]);

        $user->password = bcrypt('assodance');
        $user->save();

        // attach roles to root user
        $user->roles()->syncWithoutDetaching([
            RoleInterface::ADMIN,
            RoleInterface::USER_MANAGER,
            RoleInterface::VENDOR_MANAGER,
            RoleInterface::SERVICEMAN,
            RoleInterface::STOREKEEPER,
        ]);
    }

    /**
     * Insert local storages.
     */
    private function insertLocalStorages()
    {
        $city = $this->city->newQuery()->firstOrCreate([
            'name_ru' => 'Киев',
            'name_uk' => 'Київ',
        ]);

        $city->storages()->firstOrCreate([
            'name_ru' => 'Лукьяновка',
            'name_uk' => 'Лук\'янівка',
            'primary' => 1,
        ]);
    }

    private function insertVendors()
    {
        foreach (require app_path('Http/Controllers/Setup/Libraries/vendors.php') as $vendor) {
            Vendor::query()->firstOrCreate($vendor);
        }
    }
}
