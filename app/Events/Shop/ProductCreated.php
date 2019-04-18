<?php

namespace App\Events\Shop;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ProductCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Product
     */
    public $product;

    /**
     * Create a new event instance.
     *
     * @param Product|Model $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
