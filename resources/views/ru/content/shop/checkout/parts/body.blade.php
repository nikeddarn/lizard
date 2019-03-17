@if($cartProductsCount)

    <form class="form-checkout" method="post" action="{{ route('shop.order.store') }}">
        @csrf

        <div class="row mb-4">

            <div class="form-group col-6">
                <label for="delivery_type" class="mb-3 bold text-gray-hover">Тип доставки</label>
                <div id="delivery_type" data-courier-delivery-sum="{{ $courierDeliverySum }}">

                    @foreach($deliveryTypes as $deliveryType)
                        <div class="delivery-type custom-control custom-radio mb-2">
                            <input class="custom-control-input" type="radio" name="delivery_type"
                                   id="delivery-type-{{ $deliveryType->id }}"
                                   value="{{ $deliveryType->interface_id }}"{{ $deliveryType->interface_id === \App\Contracts\Order\DeliveryTypesInterface::SELF ? ' checked' : '' }}>
                            <label class="custom-control-label text-gray-hover"
                                   for="delivery-type-{{ $deliveryType->id }}">
                                <span>{{ $deliveryType->name_ru }}</span>
                                @if($deliveryType->interface_id === \App\Contracts\Order\DeliveryTypesInterface::COURIER)
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
                           value="{{ old('name', ($lastUserAddress ? $lastUserAddress->name : $user->name)) }}">
                </div>

                <div class="form-group">
                    <label class="bold text-gray-hover" for="delivery-phone">Номер телефона</label>
                    <input type="tel" class="form-control" id="delivery-phone" name="phone"
                           value="{{ old('phone', $lastUserAddress ? $lastUserAddress->phone : $user->phone) }}">
                </div>

            </div>

            <div class="col-12">
                <div class="row">
                    <div class="col-6">
                        <div id="delivery-city-wrapper" class="d-none form-group">
                            <label class="bold text-gray-hover" for="delivery-city">Город доставки</label>
                            <select id="delivery-city" name="city_id" class="selectpicker w-100">
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ $lastUserAddress && $lastUserAddress->cities_id === $city->id ? ' selected="selected"' : '' }}>{{ $city->name_ru }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div id="delivery-address-wrapper" class="d-none form-group">
                    <label class="bold text-gray-hover" for="delivery-address">Адрес доставки</label>
                    <input type="text" class="form-control" id="delivery-address" name="address" value="{{ $lastUserAddress ? $lastUserAddress->address : '' }}">
                </div>
            </div>

        </div>

        @if(!auth('web')->check())

            <div class="row mb-4">

                <div class="col-12">
                    <h5 class="text-gray-hover text-center mb-3">Зарегистрируйтесь чтобы получать персональные
                        скидки</h5>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label class="bold text-gray-hover" for="email">E-Mail Адрес</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="bold text-gray-hover" for="password">Пароль</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label class="bold text-gray-hover" for="password">Повторите пароль</label>
                        <input type="password" class="form-control" id="password" name="password_confirmation">
                    </div>
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
