<?php

namespace App\Http\Controllers\Sale;

use App\Events\Order\OrderUpdatedByManager;
use App\Http\Requests\Sale\StoreOrderProductRequest;
use App\Http\Requests\Sale\UpdateOrderProductRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\Orders\DeliveryPrice;
use App\Support\Shop\Products\CategoryProductsList;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderProductController extends Controller
{
    /**
     * @var Order
     */
    private $order;
    /**
     * @var OrderProduct
     */
    private $orderProduct;
    /**
     * @var DeliveryPrice
     */
    private $deliveryPrice;
    /**
     * @var Category
     */
    private $category;
    /**
     * @var Product
     */
    private $product;
    /**
     * @var ExchangeRates
     */
    private $exchangeRates;
    /**
     * @var CategoryProductsList
     */
    private $categoryProductsList;

    /**
     * OrderProductController constructor.
     * @param Order $order
     * @param OrderProduct $orderProduct
     * @param DeliveryPrice $deliveryPrice
     * @param Category $category
     * @param Product $product
     * @param ExchangeRates $exchangeRates
     * @param CategoryProductsList $categoryProductsList
     */
    public function __construct(Order $order, OrderProduct $orderProduct, DeliveryPrice $deliveryPrice, Category $category, Product $product, ExchangeRates $exchangeRates, CategoryProductsList $categoryProductsList)
    {
        $this->order = $order;
        $this->orderProduct = $orderProduct;
        $this->deliveryPrice = $deliveryPrice;
        $this->category = $category;
        $this->product = $product;
        $this->exchangeRates = $exchangeRates;
        $this->categoryProductsList = $categoryProductsList;
    }

    /**
     * Add product to order.
     *
     * @param int $orderId
     * @return View
     */
    public function create(int $orderId)
    {
        $order = $this->order->newQuery()->findOrFail($orderId);
        $categories = $this->category->withDepth()->get()->toTree();

        return view('content.sale.orders.edit.add_product.index')->with(compact('categories', 'order'));
    }

    /**
     * Get products of category.
     *
     * @param Request $request
     * @return false|string
     */
    public function categoryProducts(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $categoryId = $request->get('category_id');
        $orderId = $request->get('order_id');

        $order = $this->order->newQuery()->with('user', 'products')->findOrFail($orderId);

        $expectedProductsIds = $order->products->pluck('id')->toArray();

        $user = $order->user;
        $userPriceGroup = $user->price_group;

        $products = $this->categoryProductsList->getProducts($categoryId, $user, $expectedProductsIds);

        return view('content.sale.orders.edit.add_product.parts.products')->with(compact('order', 'products', 'userPriceGroup'));
    }

    /**
     * Store new order product.
     *
     * @param StoreOrderProductRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(StoreOrderProductRequest $request)
    {
        $orderId = $request->get('order_id');
        $order = $this->order->newQuery()->with('user')->findOrFail($orderId);

        $this->authorize('update', $order);

        $productId = $request->get('product_id');
        $product = $this->product->newQuery()->findOrFail($productId);

        $userPriceColumn = 'price' . $order->user->price_group;

        $count = 1;
        $price = $product->$userPriceColumn * $this->exchangeRates->getRate();

        if (!$price){
            return back()->withErrors([trans('validation.product_has_not_price')]);
        }

        DB::beginTransaction();
        $order->products()->attach($productId, compact('count', 'price'));
        $this->updateOrder($order, $count * $price);
        DB::commit();

        event(new OrderUpdatedByManager($order));

        return redirect(route('admin.order.show', ['order_id' => $orderId]));
    }

    /**
     * Edit order product.
     *
     * @param int $orderId
     * @param int $productId
     * @return View
     * @throws AuthorizationException
     */
    public function edit(int $orderId, int $productId)
    {
        $order = $this->order->newQuery()
            ->with(['products' => function ($query) use ($productId) {
                $query->where('id', $productId);
            }])
            ->findOrFail($orderId);

        $this->authorize('update', $order);

        return view('content.sale.orders.edit.product.index')->with(compact('order'));
    }

    /**
     * @param UpdateOrderProductRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateOrderProductRequest $request)
    {
        $orderId = $request->get('order_id');
        $productId = $request->get('product_id');

        $order = $this->order->newQuery()
            ->with(['orderProducts' => function ($query) use ($productId) {
                $query->where('products_id', $productId);
            }])
            ->with('user')
            ->findOrFail($orderId);

        $this->authorize('update', $order);

        $count = (int)$request->get('count');
        $price = (float)$request->get('price');

        $orderProduct = $order->orderProducts->first();

        $oldOrderProductSum = $orderProduct->price * $orderProduct->count;
        $newOrderProductSum = $price * $count;

        $orderAddingSum = $newOrderProductSum - $oldOrderProductSum;

        DB::beginTransaction();
        $orderProduct->update(compact('count', 'price'));
        $this->updateOrder($order, $orderAddingSum);
        DB::commit();

        event(new OrderUpdatedByManager($order));

        return redirect(route('admin.order.show', ['order_id' => $orderId]));
    }

    /**
     * Delete order product.
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function delete()
    {
        $orderId = $this->request->get('order_id');
        $productId = $this->request->get('product_id');

        $order = $this->order->newQuery()
            ->with(['orderProducts' => function ($query) use ($productId) {
                $query->where('products_id', $productId);
            }])
            ->with('user')
            ->findOrFail($orderId);

        $this->authorize('update', $order);

        $orderProduct = $order->orderProducts->first();

        $addingOrderProductSum = - $orderProduct->price * $orderProduct->count;

        DB::beginTransaction();
        $orderProduct->delete();
        $this->updateOrder($order, $addingOrderProductSum);
        DB::commit();

        event(new OrderUpdatedByManager($order));

        return redirect(route('admin.order.show', ['order_id' => $orderId]));
    }

    /**
     * Update order.
     *
     * @param Order|Model $order
     * @param float $addingOrderProductSum
     */
    private function updateOrder(Order $order, float $addingOrderProductSum)
    {
        $user = $order->user;

        $order->products_sum += $addingOrderProductSum;
        $order->delivery_sum = $this->deliveryPrice->calculateDeliveryPrice($user, $order->products_sum);
        $order->total_sum = $order->products_sum + $order->delivery_sum;
        $order->save();
    }
}
