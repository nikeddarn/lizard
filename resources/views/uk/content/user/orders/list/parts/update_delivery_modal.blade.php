<div class="modal fade" id="update-order-delivery-{{ $order->id }}" tabindex="-1" role="dialog"
     aria-labelledby="update-order-delivery-{{ $order->id }}-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <form method="post" action="{{ route('user.order.update.delivery') }}">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">

                <div class="modal-header">
                    <h5 class="modal-title text-gray-hover" id="update-order-delivery-{{ $order->id }}-title">Зміна доставки замовлення (id замовлення: <strong>{{ $order->id }}</strong>)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <div class="form-group col-6">
                            <label for="delivery_type" class="mb-3 bold text-gray-hover">Тип доставки</label>
                            <div id="delivery_type">

                                @foreach($deliveryTypes as $deliveryType)
                                    <div class="delivery-type custom-control custom-radio mb-2">
                                        <input class="custom-control-input" type="radio" name="delivery_type"
                                               id="delivery-type-{{ $deliveryType->id }}"
                                               value="{{ $deliveryType->id }}"{{ $deliveryType->id == old('delivery_type', $order->delivery_types_id) ? ' checked' : '' }}>
                                        <label class="custom-control-label text-gray-hover"
                                               for="delivery-type-{{ $deliveryType->id }}">
                                            <span>{{ $deliveryType->name_uk }}</span>
                                            @if($deliveryType->id === \App\Contracts\Order\DeliveryTypesInterface::COURIER)
                                                @if($order->courierDeliverySum > 0)
                                                    <span class="ml-1">({{ $order->courierDeliverySum }} грн)</span>
                                                @else
                                                    <span class="ml-1">(безкоштовно)</span>
                                                @endif
                                            @endif
                                        </label>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <div class="col-6">

                            <div class="form-group">
                                <label class="bold text-gray-hover" for="name">Ім'я одержувача</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ old('name', $order->orderRecipient->name) }}">
                                @if (session()->get('order_id') == $order->id && $errors->has('name'))
                                    <small class="modal-input-error text-danger">{{ $errors->first('name') }}</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="bold text-gray-hover" for="delivery-phone">Номер телефону</label>
                                <input type="tel" class="form-control" id="delivery-phone" name="phone"
                                       value="{{ old('phone', $order->orderRecipient->phone) }}">
                                @if (session()->get('order_id') == $order->id && $errors->has('phone'))
                                    <small class="modal-input-error text-danger">{{ $errors->first('phone') }}</small>
                                @endif
                            </div>

                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div id="delivery-storage-wrapper"
                                         class="form-group{{ old('delivery_type', $order->delivery_types_id) != \App\Contracts\Order\DeliveryTypesInterface::SELF ? ' d-none' : ''}}">
                                        <label class="bold text-gray-hover" for="delivery-storage">Склад самовивозу</label>
                                        <select id="delivery-storage" name="storage_id" class="selectpicker w-100">
                                            @foreach($storages as $storage)
                                                <option
                                                    value="{{ $storage->id }}" {{ old('storage_id', $order->storages_id ? $order->storages_id : ($lastSelfDeliveryOrder ? $lastSelfDeliveryOrder->storages_id : null)) == $storage->id ? ' selected="selected"' : '' }}>{{ $storage->city->name_uk }} - {{ $storage->name_uk }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div id="delivery-city-wrapper"
                                         class="form-group{{ old('delivery_type', $order->delivery_types_id) != \App\Contracts\Order\DeliveryTypesInterface::COURIER ? ' d-none' : ''}}">
                                        <label class="bold text-gray-hover" for="delivery-city">Місто доставки</label>
                                        <select id="delivery-city" name="city_id" class="selectpicker w-100">
                                            @foreach($cities as $city)
                                                <option
                                                    value="{{ $city->id }}" {{ old('city_id', ($order->orderAddress ? $order->orderAddress->cities_id : ($lastUserAddress ? $lastUserAddress->cities_id : null))) == $city->id ? ' selected="selected"' : '' }}>{{ $city->name_uk }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div id="delivery-address-wrapper"
                                 class="form-group{{ old('delivery_type', $order->delivery_types_id) == \App\Contracts\Order\DeliveryTypesInterface::SELF ? ' d-none' : ''}}">
                                <label class="bold text-gray-hover" for="delivery-address">Адреса доставки</label>
                                <input type="text" class="form-control" id="delivery-address" name="address"
                                       value="{{ old('address', ($order->orderAddress ? $order->orderAddress->address : ($lastUserAddress ? $lastUserAddress->address : null))) }}">
                                @if (session()->get('order_id') == $order->id && $errors->has('address'))
                                    <small class="modal-input-error text-danger">{{ $errors->first('address') }}</small>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
                    <button type="submit" class="btn btn-primary">Зберегти зміни</button>
                </div>

            </form>

        </div>
    </div>
</div>
