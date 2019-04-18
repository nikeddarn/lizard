<form id="order-address-edit-form" action="{{ route('admin.order.address.update') }}" method="post">
    @csrf
    <input type="hidden" name="order_id" value="{{ $order->id }}">

    <div class="row">

        <div class="col-6">

            <div class="form-group mb-4">
                <label for="delivery_type" class="mb-3 bold text-gray-hover">Тип доставки</label>
                <div id="delivery_type">

                    @foreach($deliveryTypes as $deliveryType)
                        <div class="delivery-type custom-control custom-radio mb-2">
                            <input class="custom-control-input" type="radio" name="delivery_type"
                                   id="delivery-type-{{ $deliveryType->id }}"
                                   value="{{ $deliveryType->id }}"{{ $deliveryType->id == old('delivery_type', $order->delivery_types_id) ? ' checked' : '' }}>
                            <label class="custom-control-label text-gray-hover"
                                   for="delivery-type-{{ $deliveryType->id }}">
                                <span>{{ $deliveryType->name_ru }}</span>
                                @if($deliveryType->id === \App\Contracts\Order\DeliveryTypesInterface::COURIER)
                                    @if($order->courierDeliverySum > 0)
                                        <span class="ml-1">({{ $order->courierDeliverySum }} грн)</span>
                                    @else
                                        <span class="ml-1">(бесплатно)</span>
                                    @endif
                                @endif
                            </label>
                        </div>
                    @endforeach

                </div>
            </div>

            <div id="delivery-city-wrapper"
                 class="form-group{{ old('delivery_type', $order->delivery_types_id) != \App\Contracts\Order\DeliveryTypesInterface::COURIER ? ' d-none' : ''}}">
                <label class="bold text-gray-hover" for="delivery-city">Город доставки</label>
                <select id="delivery-city" name="city_id" class="selectpicker w-100">
                    @foreach($cities as $city)
                        <option
                            value="{{ $city->id }}" {{ old('city_id', ($order->orderAddress ? $order->orderAddress->cities_id : ($lastUserAddress ? $lastUserAddress->cities_id : null))) == $city->id ? ' selected="selected"' : '' }}>{{ $city->name_ru }}</option>
                    @endforeach
                </select>
            </div>

            <div id="delivery-address-wrapper"
                 class="form-group{{ old('delivery_type', $order->delivery_types_id) == \App\Contracts\Order\DeliveryTypesInterface::SELF ? ' d-none' : ''}}">
                <label class="bold text-gray-hover" for="delivery-address">Адрес доставки</label>
                <input type="text" class="form-control" id="delivery-address" name="address"
                       value="{{ old('address', ($order->orderAddress ? $order->orderAddress->address : ($lastUserAddress ? $lastUserAddress->address : null))) }}">
                @if ($errors->has('address'))
                    <small class="modal-input-error text-danger">{{ $errors->first('address') }}</small>
                @endif
            </div>

            @can('updateDelivery', $order)
            <div class="text-right mt-5 mb-4">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
            @endcan

        </div>

    </div>

</form>
