<?php

namespace App\Listeners\Vendor;

class UpdateProductSearchIndex
{
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     * @throws \Exception
     */
    public function handle($event)
    {
        // get product
        $product = $event->product;
        $product->save();
    }
}
