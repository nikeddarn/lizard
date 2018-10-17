<?php
/**
 * Composer for all common and user view
 */

namespace App\Http\Composers;


use App\Models\Category;
use Illuminate\View\View;

class CommonDataComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     * @throws \Exception
     */
    public function compose(View $view)
    {
        $view->with('megaMenuCategories', Category::defaultOrder()->get()->toTree());
    }
}