@if($cartProductsCount)

    <form class="form-checkout form-light-background-input" method="post" action="{{ route('shop.checkout.store') }}">
        @csrf

        <div class="row mb-4">

            <div class="col-12 mb-2">
                <em class="h5 d-block text-gray-hover text-center mb-3">Укажите данные для доставки</em>
            </div>

            <div class="form-group col-6">
                <label for="delivery_type" class="mb-3 bold text-gray-hover">Тип доставки</label>
                <div id="delivery_type" data-courier-delivery-sum="{{ $courierDeliverySum }}">

                    @foreach($deliveryTypes as $deliveryType)
                        <div class="delivery-type custom-control custom-radio mb-2">
                            <input class="custom-control-input" type="radio" name="delivery_type"
                                   id="delivery-type-{{ $deliveryType->id }}"
                                   value="{{ $deliveryType->id }}"{{ $deliveryType->id == old('delivery_type', \App\Contracts\Order\DeliveryTypesInterface::SELF) ? ' checked' : '' }}>
                            <label class="custom-control-label text-gray-hover"
                                   for="delivery-type-{{ $deliveryType->id }}">
                                <span>{{ $deliveryType->name_ru }}</span>
                                @if($deliveryType->id === \App\Contracts\Order\DeliveryTypesInterface::COURIER)
                                    @if($courierDeliverySum > 0)
                                        <span class="ml-1">({{ $courierDeliverySum }} грн)</span>
                                    @else
                                        <span class="ml-1">(бесплатно)</span>
                                    @endif
                                @endif
                            </label>
                        </div>
                    @endforeach

                </div>
            </div>

            <div class="col-6">

                <div class="form-group">
                    <label class="bold text-gray-hover" for="name">Имя получателя</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name', ($lastUserRecipient ? $lastUserRecipient->name : $user->name)) }}">
                    @if ($errors->has('name'))
                        <small class="text-danger">{{ $errors->first('name') }}</small>
                    @endif
                </div>

                <div class="form-group">
                    <label class="bold text-gray-hover" for="delivery-phone">Номер телефона</label>
                    <input type="tel" class="form-control" id="delivery-phone" name="phone"
                           value="{{ old('phone', $lastUserRecipient ? $lastUserRecipient->phone : $user->phone) }}">
                    @if ($errors->has('phone'))
                        <small class="text-danger">{{ $errors->first('phone') }}</small>
                    @endif
                </div>

            </div>

            <div class="col-12">
                <div class="row">
                    <div class="col-6">
                        <div id="delivery-city-wrapper"
                             class="form-group{{ !old('delivery_type') || old('delivery_type') != \App\Contracts\Order\DeliveryTypesInterface::COURIER ? ' d-none' : ''}}">
                            <label class="bold text-gray-hover" for="delivery-city">Город доставки</label>
                            <select id="delivery-city" name="city_id" class="selectpicker w-100">
                                @foreach($cities as $city)
                                    <option
                                        value="{{ $city->id }}" {{ old('city_id', $lastUserAddress ? $lastUserAddress->cities_id : null) == $city->id ? ' selected="selected"' : '' }}>{{ $city->name_ru }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div id="delivery-address-wrapper"
                     class="form-group{{ !old('delivery_type') || old('delivery_type') == \App\Contracts\Order\DeliveryTypesInterface::SELF ? ' d-none' : ''}}">
                    <label class="bold text-gray-hover" for="delivery-address">Адрес доставки</label>
                    <input type="text" class="form-control" id="delivery-address" name="address"
                           value="{{ old('address', $lastUserAddress ? $lastUserAddress->address : '') }}">
                    @if ($errors->has('address'))
                        <small class="text-danger">{{ $errors->first('address') }}</small>
                    @endif
                </div>
            </div>

        </div>

        @if(!auth('web')->check())

            <div class="row mb-4 mt-5">

                <div class="col-12 mb-2">
                    <em class="h5 d-block text-gray-hover text-center mb-3">Для получения персональных
                        скидок</em>
                </div>

                <div class="col-12">
                    @include('content.shop.checkout.parts.login')
                </div>

            </div>

        @endif

        <div class="text-center">
            <small class="counter">ИТОГО</small>
            <h3 class="bold text-gray-hover">
                <span id="total-amount" data-amount="{{ $amount }}">{{ $formattedAmount }}</span>
                <span> грн</span>
            </h3>
            <button class="btn btn-primary rounded-pill btn-lg">
                <span>Оформить заказ</span>
            </button>
        </div>

    </form>

@else

    <div class="text-center">
        <h3 class="text-gray-hover mb-5">Ваша корзина пуста</h3>
        <a href="{{ preg_match('/category|product/', url()->previous()) ? url()->previous() : route('main') }}"
           class="btn btn-primary rounded-pill btn-lg">
            <i class="svg-icon-larger" data-feather="arrow-left"></i>
            <span>Назад в магазин</span>
        </a>
    </div>

@endif
