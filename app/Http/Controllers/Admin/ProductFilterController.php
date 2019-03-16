<?php

namespace App\Http\Controllers\Admin;

use App\Models\Filter;
use App\Models\Product;
use App\Models\ProductFilter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ProductFilterController extends Controller
{
    /**
     * @var ProductFilter
     */
    private $productFilter;
    /**
     * @var Product
     */
    private $product;
    /**
     * @var Filter
     */
    private $filter;

    /**
     * ProductFilterController constructor.
     * @param ProductFilter $productFilter
     * @param Product $product
     * @param Filter $filter
     */
    public function __construct(ProductFilter $productFilter, Product $product, Filter $filter)
    {
        $this->productFilter = $productFilter;
        $this->product = $product;
        $this->filter = $filter;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function create(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $locale = app()->getLocale();

        $product = $this->product->newQuery()->findOrFail($id);

        $filters = $this->filter->newQuery()->orderBy("name_$locale")->get();

        return view('content.admin.catalog.product_filter.create.index')->with([
            'product' => $product,
            'filters' => $filters,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $productsId = $request->get('products_id');

        $this->validate($request, [
            'filters_id' => ['integer', Rule::unique('product_filter', 'filters_id')->where('products_id', $productsId)],
            'products_id' => ['integer'],
        ]);


        $this->productFilter->newQuery()->create($request->only(['products_id', 'filters_id']));

        return redirect(route('admin.products.show', ['id' => $productsId]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $productsId = $request->get('products_id');
        $filtersId = $request->get('filters_id');

        $this->productFilter->newQuery()->where(['products_id' => $productsId, 'filters_id' => $filtersId])->delete();

        return redirect(route('admin.products.show', ['id' => $productsId]));
    }
}
