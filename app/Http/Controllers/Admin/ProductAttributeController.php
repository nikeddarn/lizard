<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductAttribute\StoreProductAttributeRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ProductAttributeController extends Controller
{
    /**
     * @var ProductAttribute
     */
    private $productAttribute;
    /**
     * @var Product
     */
    private $product;
    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var AttributeValue
     */
    private $attributeValue;

    /**
     * ProductAttributeController constructor.
     * @param Product $product
     * @param ProductAttribute $productAttribute
     * @param Attribute $attribute
     * @param AttributeValue $attributeValue
     */
    public function __construct(Product $product, ProductAttribute $productAttribute, Attribute $attribute, AttributeValue $attributeValue)
    {
        $this->productAttribute = $productAttribute;
        $this->product = $product;
        $this->attribute = $attribute;
        $this->attributeValue = $attributeValue;
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

        $attributes = $this->attribute->newQuery()
            ->has('attributeValues')
            ->orderBy("name_$locale")
            ->with(['attributeValues' => function($query) use ($locale) {
                $query->orderBy("value_$locale")->select(['id', 'attributes_id', "value_$locale as name"]);
            }])
            ->get()
            ->keyBy('id');

        return view('content.admin.catalog.product_attribute.create.index')->with(compact('product', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductAttributeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductAttributeRequest $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $productsId = $request->get('products_id');

        $this->productAttribute->newQuery()->create($request->only(['products_id', 'attributes_id', 'attribute_values_id']));

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
        $attributeValuesId = $request->get('attribute_values_id');

        $this->productAttribute->newQuery()->where(['products_id' => $productsId, 'attribute_values_id' => $attributeValuesId])->delete();

        return redirect(route('admin.products.show', ['id' => $productsId]));
    }
}
