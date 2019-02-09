<?php

namespace App\Listeners\Shop;

class UpdateParentCategoryTimestamp
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // get product
        $category = $event->category;

        // update parent category timestamp
        if ($category->parent_id){
            $category->parent->touch();
        }
    }
}
