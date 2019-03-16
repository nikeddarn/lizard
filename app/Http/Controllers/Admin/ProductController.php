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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Gate::denies('local-catalog-show', auth('web')->user())) {
            abort(401);
        }

        $locale = app()->getLocale();

        $query = $this->product->newQuery()
            ->leftJoin('vendor_products', 'vendor_products.products_id', '=', 'products.id')
            ->leftJoin('vendors', 'vendor_products.vendors_id', '=', 'vendors.id')
            ->leftJoin('category_product', 'category_product.products_id', '=', 'products.id')
            ->leftJoin('categories', 'category_product.categories_id', '=', 'categories.id')
            ->selectRaw("products.*, vendors.name_$locale AS vendor_name, categories.name_$locale AS category_name")
            ->with('categories', 'primaryImage', 'vendors');

        if ($request->has('sortBy')) {
            $query = $this->addSortByConstraint($request, $query);
        }

        $products = $query->paginate(config('admin.show_items_per_page'))->appends(request()->query());

        return view('content.admin.catalog.product.list.index')->with([
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

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
     */
    public function store(CreateProductRequest $request, ProductImageHandler $imageHandler)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

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
     */
    public function show(string $id)
    {
        if (Gate::denies('local-catalog-show', auth('web')->user())) {
            abort(401);
        }

        $product = $this->product->newQuery()->with('productImages', 'productAttributes.attributeValue.attribute', 'filters', 'categories', 'brand', 'vendors')->findOrFail($id);

        return view('content.admin.catalog.product.show.index')->with(compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

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
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

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
     * @throws \Exception
     */
    public function destroy(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        // retrieve product
        $product = $this->product->newQuery()
            ->with('storages', 'categories', 'vendorProducts', 'stockStorages')
            ->findOrFail($id);

        // product presents or reserved in stock
        if ($product->stockStorages->count()) {
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
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        if (!($request->ajax() && $request->hasFile('image'))) {
            return abort(405);
        }

        $this->validate($request, ['image' => 'image']);

        return '/storage/' . $imageHandler->insertProductDescriptionImage($request->image);
    }

    /**
     * Set product published.
     *
     * @return bool|RedirectResponse
     */
    public function publishProduct()
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        Product::withoutSyncingToSearch(function () {

            $productId = (int)request()->get('product_id');

            $product = $this->product->newQuery()->findOrFail($productId);

            $product->published = 1;
            $product->save();
        });

        if (request()->ajax()) {
            return 'true';
        } else {
            return back();
        }
    }

    /**
     * Set product un published.
     *
     * @return bool|RedirectResponse
     */
    public function unPublishProduct()
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        Product::withoutSyncingToSearch(function () {

            $productId = (int)request()->get('product_id');

            $product = $this->product->newQuery()->findOrFail($productId);

            $product->published = 0;
            $product->save();
        });

        if (request()->ajax()) {
            return 'true';
        } else {
            return back();
        }
    }

    /**
     * Create and store images.
     *
     * @param ProductImageHandler $imageHandler
     * @param CreateProductRequest $request
     * @param Product|Model $product
     */
    private function insertProductImages(ProductImageHandler $imageHandler, CreateProductRequest $request, Product $product)
    {
        $priorityImageIndex = (int)$request->get('priority');

        foreach ($request->image as $index => $image) {
            // image priority
            $priority = $index === $priorityImageIndex ? 1 : 0;

            // create product images
            $imageHandler->insertProductImage($product, $image, $priority);
        }
    }

    /**
     * Add sort by condition.
     *
     * @param Request $request
     * @param Builder $query
     * @return Builder
     */
    private function addSortByConstraint(Request $request, Builder $query): Builder
    {
        switch ($request->get('sortBy')) {
            case 'createdAt' :
                if ($request->get('sortMethod') === 'asc') {
                    $query->orderBy('created_at');
                } else if ($request->get('sortMethod') === 'desc') {
                    $query->orderByDesc('created_at');
                }
                break;

            case 'published' :
                if ($request->get('sortMethod') === 'asc') {
                    $query->orderByDesc('published');
                } else if ($request->get('sortMethod') === 'desc') {
                    $query->orderBy('published');
                }
                break;

            case 'archived':
                if ($request->get('sortMethod') === 'asc') {
                    $query->orderByDesc('is_archive');
                } else if ($request->get('sortMethod') === 'desc') {
                    $query->orderBy('is_archive');
                }
                break;

            case 'name':
                $locale = app()->getLocale();

                if ($request->get('sortMethod') === 'asc') {
                    $query->orderBy('name_' . $locale);
                } else if ($request->get('sortMethod') === 'desc') {
                    $query->orderByDesc('name_' . $locale);
                }
                break;

            case 'category':
                if ($request->get('sortMethod') === 'asc') {
                    $query->orderBy('category_name');
                } else if ($request->get('sortMethod') === 'desc') {
                    $query->orderByDesc('category_name');
                }
                break;

            case 'vendor':
                if ($request->get('sortMethod') === 'asc') {
                    $query->orderBy('vendor_name');
                } else if ($request->get('sortMethod') === 'desc') {
                    $query->orderByDesc('vendor_name');
                }
                break;
        }

        return $query;
    }
}
