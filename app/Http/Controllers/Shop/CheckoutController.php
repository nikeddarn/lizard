<?php

namespace App\Http\Controllers\Shop;

use App\Contracts\Order\DeliveryTypesInterface;
use App\Contracts\Order\OrderStatusInterface;
use App\Events\Order\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\StoreOrderRequest;
use App\Models\City;
use App\Models\DeliveryType;
use App\Models\FavouriteProduct;
use App\Models\Order;
use App\Models\OrderRecipient;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\RecentProduct;
use App\Models\User;
use App\Models\OrderAddress;
use App\Models\UserCartProduct;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\Orders\DeliveryPrice;
use App\Support\Shop\Products\CartProducts;
use App\Support\User\RetrieveUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    use RetrieveUser;

    /**
     * @var OrderAddress
     */
    private $orderAddress;
    /**
     * @var Order
     */
    private $order;
    /**
     * @var DeliveryPrice
     */
    private $deliveryPrice;
    /**
     * @var UserCartProduct
     */
    private $userCartProduct;
    /**
     * @var OrderStatus
     */
    private $orderStatus;
    /**
     * @var User
     */
    private $user;
    /**
     * @var ExchangeRates
     */
    private $exchangeRates;
    /**
     * @var OrderRecipient
     */
    private $orderRecipient;
    /**
     * @var FavouriteProduct
     */
    private $favouriteProduct;
    /**
     * @var RecentProduct
     */
    private $recentProduct;

    /**
     * OrderController constructor.
     * @param OrderAddress $orderAddress
     * @param OrderRecipient $orderRecipient
     * @param Order $order
     * @param DeliveryPrice $deliveryPrice
     * @param UserCartProduct $userCartProduct
     * @param OrderStatus $orderStatus
     * @param User $user
     * @param ExchangeRates $exchangeRates
     * @param FavouriteProduct $favouriteProduct
     * @param RecentProduct $recentProduct
     */
    public function __construct(OrderAddress $orderAddress, OrderRecipient $orderRecipient, Order $order, DeliveryPrice $deliveryPrice, UserCartProduct $userCartProduct, OrderStatus $orderStatus, User $user, ExchangeRates $exchangeRates, FavouriteProduct $favouriteProduct, RecentProduct $recentProduct)
    {
        $this->orderAddress = $orderAddress;
        $this->order = $order;
        $this->deliveryPrice = $deliveryPrice;
        $this->userCartProduct = $userCartProduct;
        $this->orderStatus = $orderStatus;
        $this->user = $user;
        $this->exchangeRates = $exchangeRates;
        $this->orderRecipient = $orderRecipient;
        $this->favouriteProduct = $favouriteProduct;
        $this->recentProduct = $recentProduct;
    }

    /**
     * Show checkout form.
     *
     * @param DeliveryType $deliveryType
     * @param City $city
     * @param CartProducts $cartProducts
     * @return View
     */
    public function create(DeliveryType $deliveryType, City $city, CartProducts $cartProducts)
    {
        $user = $this->getOrCreateUser();

        $lastUserAddress = $user->orderAddresses()->orderByDesc('id')->first();
        $lastUserRecipient = $user->orderRecipients()->orderByDesc('id')->first();

        $products = $cartProducts->getProducts($user);

        $cartProductsCount = $products->count();

        // calculate product's total amount
        $amount = round($products->sum(function (Product $product) {
            return $product->localPrice * $product->pivot->count;
        }));

        $formattedAmount = number_format($amount);

        // calculate delivery sum
        $courierDeliverySum = $this->deliveryPrice->calculateDeliveryPrice($user, $amount);

        $deliveryTypes = $deliveryType->newQuery()->get();

        $cities = $city->newQuery()->has('storages')->get();

        return view('content.shop.checkout.index')->with(compact('user', 'lastUserAddress', 'lastUserRecipient', 'deliveryTypes', 'cartProductsCount', 'cities', 'amount', 'formattedAmount', 'courierDeliverySum'));
    }

    /**
     * Store request.
     *
     * @param StoreOrderRequest $request
     * @return RedirectResponse
     */
    public function store(StoreOrderRequest $request)
    {
        $user = $this->getUserOrFail();
        $cartProducts = $user->userCartProducts()->with('product')->get();

        // login or register
        if (auth('web')->check()) {
            $loggedInUser = auth('web')->user();
        } else {
            if ($request->filled('login_email') && $request->filled('login_password')) {
                $loggedInUser = $this->loginUser($request);

                if (!$loggedInUser) {
                    return back()->withErrors(['login_email' => trans('auth.failed')]);
                }
            } else {
                $this->registerUser($request, $user);
                $loggedInUser = $user;
            }
        }

        DB::beginTransaction();
        $order = $this->createOrder($request, $loggedInUser);
        $this->attachOrderProducts($order, $cartProducts, $loggedInUser);
        $this->clearUserCart($user);
        $this->mergeUsersData($user, $loggedInUser);
        DB::commit();

        event(new OrderCreated($order));

        return redirect(route('user.orders.index'));
    }

    /**
     * Create user order.
     *
     * @param StoreOrderRequest $request
     * @param $user
     * @return Order|Model
     */
    private function createOrder(StoreOrderRequest $request, $user): Order
    {
        $deliveryTypeId = (int)$request->get('delivery_type');

        // create order recipient
        $orderRecipient = $this->orderRecipient->newQuery()->firstOrCreate([
            'users_id' => $user->id,
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
        ]);

        // create order delivery address
        if ($deliveryTypeId !== DeliveryTypesInterface::SELF) {
            $orderAddressData = [
                'users_id' => $user->id,
                'address' => $request->get('address'),
            ];

            if ($deliveryTypeId === DeliveryTypesInterface::COURIER) {
                $orderAddressData['cities_id'] = $request->get('city_id');
            }

            $orderAddress = $this->orderAddress->newQuery()->firstOrCreate($orderAddressData);

            $orderAddressId = $orderAddress->id;
        } else {
            $orderAddressId = null;
        }

        // create order
        $orderData = [
            'order_status_id' => OrderStatusInterface::HANDLING,
            'delivery_types_id' => $deliveryTypeId,
            'users_id' => $user->id,
            'order_recipients_id' => $orderRecipient->id,
            'order_addresses_id' => $orderAddressId,
        ];

        $order = $this->order->newQuery()->create($orderData);

        // add 'user' relation
        $order->setRelation('user', $user);

        return $order;
    }

    /**
     * Attach products to order.
     *
     * @param Order $order
     * @param Collection $cartProducts
     * @param $user
     */
    private function attachOrderProducts(Order $order, Collection $cartProducts, $user)
    {
        $userProductPriceColumn = 'price' . $user->price_group;

        $orderUahSum = 0;

        foreach ($cartProducts as $cartProduct) {
            $price = $cartProduct->product->$userProductPriceColumn * $this->exchangeRates->getRate();
            $count = $cartProduct->count;

            $order->products()->attach($cartProduct->products_id, compact('price', 'count'));

            $orderUahSum += $price * $count;
        }

        $deliverySum = $this->deliveryPrice->calculateDeliveryPrice($user, $orderUahSum);

        $order->products_sum = $orderUahSum;
        $order->delivery_sum = $deliverySum;
        $order->total_sum = $order->products_sum + $order->delivery_sum;
        $order->save();
    }

    /**
     * Clear user cart.
     *
     * @param $user
     */
    private function clearUserCart($user)
    {
        $this->userCartProduct->newQuery()->where('users_id', $user->id)->delete();
    }

    /**
     * Delete temporary user.
     *
     * @param $user
     * @param $loggedInUser
     */
    private function mergeUsersData($user, $loggedInUser)
    {
        if ($user->id !== $loggedInUser->id) {
            // associate favourite products with new user
            $this->favouriteProduct->newQuery()->where('users_id', $user->id)->update([
                'users_id' => $loggedInUser->id,
            ]);

            // associate recent products with new user
            $this->recentProduct->newQuery()->where('users_id', $user->id)->update([
                'users_id' => $loggedInUser->id,
            ]);

            // delete unregistered user
            $user->delete();
        }
    }

    /**
     * Log in user.
     *
     * @param StoreOrderRequest $request
     * @return Authenticatable|null
     */
    private function loginUser(StoreOrderRequest $request)
    {
        if (auth('web')->attempt([
            'email' => $request->get('login_email'),
            'password' => $request->get('login_password'),
        ], $request->filled('remember'))) {
            return auth('web')->user();
        } else {
            return null;
        }
    }

    /**
     * Create user.
     *
     * @param StoreOrderRequest $request
     * @param $user
     */
    private function registerUser(StoreOrderRequest $request, $user)
    {
        $user->update([
            'name' => $request->get('name'),
            'email' => $request->get('register_email'),
            'password' => Hash::make($request->get('password')),
            'phone' => $request->get('phone'),
        ]);

        /** @noinspection PhpParamsInspection */
        auth()->login($user);
    }
}
