<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Shop\ProductBadgesInterface;
use App\Http\Requests\Admin\Product\CreateProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Filter;
use App\Models\Product;
use App\Support\ImageHandlers\ProductImageHandler;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var Category
     */
    private $category;
    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var Filter
     */
    private $filter;
    /**
     * @var Brand
     */
    private $brand;

    /**
     * CategoryController constructor.
     * @param Product $product
     * @param Category $category
     * @param Attribute $attribute
     * @param Filter $filter
     * @param Brand $brand
     */
    public function __construct(Product $product, Category $category, Attribute $attribute, Filter $filter, Brand $brand)
    {
        $this->product = $product;
        $this->category = $category;
        $this->attribute = $attribute;
        $this->filter = $filter;
        $this->brand = $brand;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', $this->product);

        $products = $this->product->newQuery()->with('categories', 'primaryImage', 'vendors')->paginate(config('admin.show_items_per_page'));

        return view('content.admin.catalog.product.list.index')->with([
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->product);

        $locale = app()->getLocale();

        $categories = $this->category->withDepth()->get()->toTree();

        $attributes = $this->attribute->newQuery()
            ->has('attributeValues')
            ->orderBy("name_$locale")
            ->with(['attributeValues' => function ($query) use ($locale) {
                $query->orderBy("value_$locale")->select(['id', 'attributes_id', "value_$locale as name"]);
            }])
            ->get()
            ->keyBy('id');

        $filters = $this->filter->newQuery()->orderBy("name_$locale")->get();

        $brands = $this->brand->newQuery()->get();

        // redirect to create category if no one category exists
        if (!$categories->count()) {
            return redirect(route('admin.categories.create'))->withErrors(['no_categories' => trans('validation.no_categories')]);
        }

        return view('content.admin.catalog.product.create.index')->with(compact('categories', 'attributes', 'filters', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateProductRequest $request
     * @param ProductImageHandler $imageHandler
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateProductRequest $request, ProductImageHandler $imageHandler)
    {
        $this->authorize('create', $this->attribute);

        $productData = $request->only(['name_ru', 'name_uk', 'model_ru', 'model_uk', 'articul', 'code', 'url', 'title_ru', 'title_uk', 'description_ru', 'description_uk', 'keywords_ru', 'keywords_uk', 'brief_content_ru', 'brief_content_uk', 'content_ru', 'content_uk', 'manufacturer_ru', 'manufacturer_uk', 'min_order_quantity', 'price1', 'price2', 'price3', 'is_new', 'warranty', 'weight', 'length', 'width', 'height', 'volume']);

        // calculate product volume
        if (!$request->get('volume') && $request->has(['length', 'width', 'height'])) {
            $productData['volume'] = ($request->get('length') * $request->get('width') * $request->get('height')) / pow(10, 6);
        }

        // insert brand
        $brandId = $request->get('brands_id');
        if ($brandId > 0) {
            $productData['brands_id'] = $brandId;
        }

        $product = $this->product->newQuery()->create($productData);

        // insert images
        if ($request->has('image')) {
            $this->insertProductImages($imageHandler, $request, $product);
        }

        // insert attributes
        if ($request->has('attribute_id')) {

            $attributesValues = $request->get('attribute_value_id');

            foreach ($request->get('attribute_id') as $key => $attributeId) {
                if ($attributeId) {
                    $product->attributes()->attach($attributeId, ['attribute_values_id' => $attributesValues[$key]]);
                }
            }
        }

        // insert filters
        if ($request->has('filter_id')) {
            foreach (array_filter(array_unique($request->get('filter_id'))) as $filterId) {
                $product->filters()->attach($filterId);
            }
        }

        // insert categories
        if ($request->has('categories_id')) {
            foreach (array_filter(array_unique($request->get('categories_id'))) as $categoryId) {
                $product->categories()->attach($categoryId);
            }
        }

        // add 'new' badge
        $newProductBadgeExpired = Carbon::now()->addDays(config('shop.badges.ttl.' . ProductBadgesInterface::NEW));
        $product->badges()->attach(ProductBadgesInterface::NEW, ['expired' => $newProductBadgeExpired]);

        return redirect(route('admin.products.show', ['id' => $product->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(string $id)
    {
        $this->authorize('view', $this->product);

        $product = $this->product->newQuery()->with('productImages', 'productAttributes.attributeValue.attribute', 'filters', 'categories', 'brand', 'vendors')->findOrFail($id);

        return view('content.admin.catalog.product.show.index')->with(compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(string $id)
    {
        $this->authorize('update', $this->product);

        $product = $this->product->newQuery()->with('brand')->findOrFail($id);

        $brands = $this->brand->newQuery()->get();

        return view('content.admin.catalog.product.update.index')->with(compact('product', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductRequest $request
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $this->authorize('update', $this->product);

        $productData = $request->only(['name_ru', 'name_uk', 'model_ru', 'model_uk', 'articul', 'code', 'url', 'title_ru', 'title_uk', 'description_ru', 'description_uk', 'keywords_ru', 'keywords_uk', 'brief_content_ru', 'brief_content_uk', 'content_ru', 'content_uk', 'manufacturer_ru', 'manufacturer_uk', 'min_order_quantity', 'price1', 'price2', 'price3', 'is_new', 'warranty', 'weight', 'length', 'width', 'height', 'volume']);

        // calculate product volume
        if (!$request->get('volume') && $request->has(['length', 'width', 'height'])) {
            $productData['volume'] = ($request->get('length') * $request->get('width') * $request->get('height')) / pow(10, 6);
        }

        // insert brand
        $brandId = (int)$request->get('brands_id');
        if ($brandId === 0) {
            $productData['brands_id'] = null;
        } else {
            $productData['brands_id'] = $brandId;
        }

        $product = $this->product->newQuery()->findOrFail($id);

        if ($productData['price1'] < $product->price1 || $productData['price2'] < $product->price2 || $productData['price3'] < $product->price3) {
            // add 'discount' badge
            $discountProductBadgeExpired = Carbon::now()->addDays(config('shop.badges.ttl.' . ProductBadgesInterface::PRICE_DOWN));
            $product->badges()->syncWithoutDetaching([ProductBadgesInterface::PRICE_DOWN => ['expired' => $discountProductBadgeExpired]]);
        }

        $product->update($productData);

        return redirect(route('admin.products.show', ['id' => $id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', $this->product);

        // retrieve product
        $product = $this->product->newQuery()
            ->with('storages', 'categories', 'vendorProducts', 'stockStorages')
            ->findOrFail($id);

        // product presents or reserved in stock
        if ($product->stockStorages->count()){
            return back()->withErrors([trans('validation.product_in_stock')]);
        }

        // delete or archive product (via 'deleting' event listeners)
        $product->delete();

        return redirect(route('admin.products.index'));
    }

    /**
     * Store image on public disk. Return image url.
     *
     * @param Request $request
     * @param ProductImageHandler $imageHandler
     * @return string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function uploadImage(Request $request, ProductImageHandler $imageHandler)
    {
        if (!($request->ajax() && $request->hasFile('image'))) {
            return abort(405);
        }

        $this->validate($request, ['image' => 'image']);

        return '/storage/' . $imageHandler->insertProductDescriptionImage($request->image);
    }

    /**
     * Create and store images.
     *
     * @param ProductImageHandler $imageHandler
     * @param CreateProductRequest $request
     * @param Product|Model $product
     */
    public function insertProductImages(ProductImageHandler $imageHandler, CreateProductRequest $request, Product $product)
    {
        $priorityImageIndex = (int)$request->get('priority');

        foreach ($request->image as $index => $image) {
            // image priority
            $priority = $index === $priorityImageIndex ? 1 : 0;

            // create product images
            $imageHandler->insertProductImage($product, $image, $priority);
        }
    }
}
