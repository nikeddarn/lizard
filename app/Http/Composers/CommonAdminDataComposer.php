<?php
/**
 * Append common data for all admin views.
 */

namespace App\Http\Composers;


use App\Models\Vendor;
use Illuminate\View\View;

class CommonAdminDataComposer
{
    /**
     * @var Vendor
     */
    private $vendor;

    /**
     * CommonDataComposer constructor.
     * @param Vendor $vendor
     */
    public function __construct(Vendor $vendor)
    {
        $this->vendor = $vendor;
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     * @throws \Exception
     */
    public function compose(View $view)
    {
        // get locale
        $locale = app()->getLocale();

        $vendors = $this->vendor->newQuery()->orderBy("name_$locale")->get();

        $user = auth('web')->user();

        $view->with(compact('user', 'vendors'));
    }
}
