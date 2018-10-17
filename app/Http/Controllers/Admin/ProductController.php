<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Product\CreateProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Filter;
use App\Models\Product;
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
     * CategoryController constructor.
     * @param Product $product
     * @param Category $category
     * @param Attribute $attribute
     * @param Filter $filter
     */
    public function __construct(Product $product, Category $category, Attribute $attribute, Filter $filter)
    {
        $this->product = $product;
        $this->category = $category;
        $this->attribute = $attribute;
        $this->filter = $filter;
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

        $products = $this->product->newQuery()->with('category', 'primaryImage')->paginate(config('admin.show_items_per_page'));

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

        $categories = $this->category->withDepth()->get();

        $attributes = $this->attribute->newQuery()
            ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attributes_id')
            ->selectRaw("attributes.id AS id, attributes.name_$locale AS name_$locale, CONCAT('[', GROUP_CONCAT(JSON_OBJECT('id', attribute_values.id, 'value',  attribute_values.value_$locale) ORDER BY attribute_values.value_$locale ASC SEPARATOR ','), ']') AS attribute_values")
            ->groupBy('attributes.id')
            ->orderBy("attributes.name_$locale")
            ->get();

        $filters = $this->filter->newQuery()->orderBy("name_$locale")->get();

        // redirect to create category if no one category exists
        if (!$categories->count()) {
            return redirect(route('admin.categories.create'))->withErrors(['no_categories' => trans('validation.no_categories')]);
        }

        return view('content.admin.catalog.product.create.index')->with([
            'categories' => $categories->toTree(),
            'attributes' => $attributes,
            'filters' => $filters,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateProductRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateProductRequest $request)
    {
        $this->authorize('create', $this->attribute);

        $productData = $request->only(['name_ru', 'name_ua', 'url', 'categories_id', 'title_ru', 'title_ua', 'description_ru', 'description_ua', 'keywords_ru', 'keywords_ua', 'content_ru', 'content_ua', 'price1', 'price2', 'price3']);

        $product = $this->product->newQuery()->create($productData);

        // insert images
        if ($request->has('image')) {

            $priorityImageIndex = (int)$request->get('priority');

            foreach ($request->image as $index => $image) {

                $productImageData = ['image' => $image->store('images/products', 'public')];

                if ($index === $priorityImageIndex) {
                    $productImageData['priority'] = 1;
                }

                $product->productImages()->create($productImageData);
            }
        }

        // insert attributes
        if ($request->has('attribute_value_id')) {
            foreach (array_filter(array_unique($request->get('attribute_value_id'))) as $attributeValueId) {
                $product->attributeValues()->attach($attributeValueId);
            }
        }

        // insert filters
        if ($request->has('filter_id')) {
            foreach (array_filter(array_unique($request->get('filter_id'))) as $filterId) {
                $product->filters()->attach($filterId);
            }
        }

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

        $product = $this->product->newQuery()->with('productImages', 'productAttributes.attributeValue.attribute', 'filters', 'category')->findOrFail($id);

        return view('content.admin.catalog.product.show.index')->with([
            'product' => $product,
        ]);
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

        $categories = $this->category->withDepth()->get();

        return view('content.admin.catalog.product.update.index')->with([
            'categories' => $categories->toTree(),
            'product' => $this->product->newQuery()->findOrFail($id),
        ]);
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

        $productData = $request->only(['name_ru', 'name_ua', 'url', 'categories_id', 'title_ru', 'title_ua', 'description_ru', 'description_ua', 'keywords_ru', 'keywords_ua', 'content_ru', 'content_ua', 'price1', 'price2', 'price3']);

        $product = $this->product->newQuery()->findOrFail($id)->update($productData);

        return redirect(route('admin.products.show', ['id' => $id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', $this->product);

        $this->product->newQuery()->findOrFail($id)->delete();

        return redirect(route('admin.products.index'));
    }

    /**
     * Store image on public disk. Return image url.
     *
     * @param Request $request
     * @return string
     */
    public function uploadImage(Request $request)
    {
        if (!($request->ajax() && $request->hasFile('image'))) {
            return abort(405);
        }

        $this->validate($request, ['image' => 'image']);

        return '/storage/' . $request->image->store('images/products/content', 'public');
    }
}
