<?php

namespace App\Listeners\Vendor;

use Illuminate\Support\Facades\Log;

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

//        $product->searchable();
        Log::info('se');
    }
}
