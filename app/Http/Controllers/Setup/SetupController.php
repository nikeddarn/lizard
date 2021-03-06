<?php

namespace App\Http\Controllers\Setup;

use App\Contracts\Auth\RoleInterface;
use App\Models\Attribute;
use App\Models\Badge;
use App\Models\CastProductMethod;
use App\Models\City;
use App\Models\Dealer;
use App\Models\DeliveryType;
use App\Models\OrderStatus;
use App\Models\Role;
use App\Models\Slider;
use App\Models\StaticPage;
use App\Models\Storage;
use App\Models\StorageDepartment;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\WorkDay;
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
//        $this->fillLibraries();
//        $this->insertLocalStorages();
//        $this->insertVendors();
        $this->insertDealers();
//        $this->setupStaticPages();

        return view('elements.setup.setup_complete');
    }

    /**
     * Insert default users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setupUsers()
    {
        $this->insertRootUsers();

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
            return view('elements.setup.setup_complete')->withErrors([$exception->getMessage()]);
        }

        return view('elements.setup.setup_complete');
    }

    /**
     * Setup static pages and slider.
     */
    public function setupStaticPages()
    {
        // create static pages data
        foreach (require app_path('Http/Controllers/Setup/Libraries/static_pages.php') as $page) {
            StaticPage::query()->firstOrCreate($page);
        }

        // create main slider
//        Slider::query()->firstOrCreate([
//            'key' => 'main_page_top_slider',
//            'name_ru' => 'Основной слайдер главной страницы',
//            'name_uk' => 'Основний слайдер головної сторінки',
//        ]);
    }

    /**
     * Fill library models.
     */
    private function fillLibraries()
    {
        // fill roles
        foreach (require app_path('Http/Controllers/Setup/Libraries/roles.php') as $role) {
            Role::query()->firstOrCreate($role);
        }

        // fill storages departments
        foreach (require app_path('Http/Controllers/Setup/Libraries/storage_departments.php') as $department) {
            StorageDepartment::query()->firstOrCreate($department);
        }

        // fill badges
        foreach (require app_path('Http/Controllers/Setup/Libraries/product_badges.php') as $badge) {
            Badge::query()->firstOrCreate($badge);
        }

        // fill attributes
        foreach (require app_path('Http/Controllers/Setup/Libraries/attributes.php') as $attribute) {
            Attribute::query()->firstOrCreate($attribute);
        }

        // fill cast product methods
        foreach (require app_path('Http/Controllers/Setup/Libraries/cast_product_methods.php') as $method) {
            CastProductMethod::query()->firstOrCreate($method);
        }

        // fill delivery types
        foreach (require app_path('Http/Controllers/Setup/Libraries/delivery_types.php') as $deliveryType) {
            DeliveryType::query()->firstOrCreate($deliveryType);
        }

        // fill order status
        foreach (require app_path('Http/Controllers/Setup/Libraries/order_status.php') as $orderStatus) {
            OrderStatus::query()->firstOrCreate($orderStatus);
        }

        // fill work days
        foreach (require app_path('Http/Controllers/Setup/Libraries/work_days.php') as $workDay) {
            WorkDay::query()->firstOrCreate($workDay);
        }
    }

    /**
     * Create root user with all roles.
     *
     * @return void
     */
    private function insertRootUsers()
    {
        // create root user
        $user = $this->user->newQuery()->firstOrNew([
            'name' => 'Nikeddarn',
            'email' => 'nikeddarn@gmail.com',
        ]);

        $user->password = bcrypt('assodance3791');
        $user->save();

        // attach roles to root user
        $user->roles()->syncWithoutDetaching([
            RoleInterface::ADMIN,
        ]);

        // create demo user
        $user = $this->user->newQuery()->firstOrNew([
            'name' => 'demo',
            'email' => 'demo@gmail.com',
        ]);

        $user->password = bcrypt('demo');
        $user->save();

        // attach roles to root user
        $user->roles()->syncWithoutDetaching([
            RoleInterface::ADMIN,
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

        $storage = Storage::query()->where('name_ru', 'Лукьяновка')->first();

        if (!$storage) {
            $storage = Storage::query()->firstOrCreate([
                'cities_id' => $city->id,
                'name_ru' => 'Лукьяновка',
                'name_uk' => 'Лук\'янівка',
                'primary' => 1,
                'address_ru' => 'Киев, улица Дегтяревская 21',
                'address_uk' => 'Київ, вулиця Дегтярівська 21',
                'longitude' => 30.461027,
                'latitude' => 50.461107,
            ]);
        }


        $storagePhones = ['(098) 096-57-72', '(066) 384-43-59', '(093) 956-59-23'];

        foreach ($storagePhones as $phone) {
            $storage->storagePhones()->firstOrCreate([
                'phone' => $phone,
            ]);
        }

        $workDayId = WorkDay::query()->where('name_ru', 'Пн - Пт')->first()->id;
        $storage->workDays()->syncWithoutDetaching([
            $workDayId => [
                'start_time' => 9,
                'end_time' => 18,
            ],
        ]);
    }

    private function insertVendors()
    {
        foreach (require app_path('Http/Controllers/Setup/Libraries/vendors.php') as $vendor) {
            Vendor::query()->firstOrCreate($vendor);
        }
    }

    private function insertDealers()
    {
        foreach (require app_path('Http/Controllers/Setup/Libraries/dealers.php') as $vendor) {
            Dealer::query()->firstOrCreate($vendor);
        }
    }
}
