<?php


namespace App\Support\Orders;


use App\Models\Order;
use App\Models\Storage;
use Illuminate\Database\Eloquent\Model;

class OrderStorage
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * OrderStorage constructor.
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Define order storage.
     *
     * @param Order|Model $order
     * @return bool
     */
    public function setOrderStorage(Order $order)
    {
        if ($order->storages_id) {
            return true;
        }

        // get order city id
        $orderCityId = $order->orderAddress ? $order->orderAddress->cities_id : null;

            // ToDo. Manual select storage (by manager) via form if city not defined or count of city's storages > 1 (return false)

        if ($orderCityId) {
            // get storage by city id
            $storageId = $this->storage->newQuery()
                ->whereHas('city', function ($query) use ($orderCityId) {
                    $query->where('id', $orderCityId);
                })
                ->first()->id;
        } else {
            // get main storage
            $storageId = $this->storage->newQuery()->where('primary', 1)->first()->id;
        }

        // set order storage
        $order->storages_id = $storageId;
        $order->save();

        return true;
    }
}
