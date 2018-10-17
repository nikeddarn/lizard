<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

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
     * ProductAttributeController constructor.
     * @param Product $product
     * @param ProductAttribute $productAttribute
     * @param Attribute $attribute
     */
    public function __construct(Product $product, ProductAttribute $productAttribute, Attribute $attribute)
    {
        $this->productAttribute = $productAttribute;
        $this->product = $product;
        $this->attribute = $attribute;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(string $id)
    {
        $this->authorize('create', $this->productAttribute);

        $locale = app()->getLocale();

        $product = $this->product->newQuery()->findOrFail($id);

        $attributes = $this->attribute->newQuery()
            ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attributes_id')
            ->selectRaw("attributes.id AS id, attributes.name_$locale AS name_$locale, CONCAT('[', GROUP_CONCAT(JSON_OBJECT('id', attribute_values.id, 'value',  attribute_values.value_$locale) ORDER BY attribute_values.value_$locale ASC SEPARATOR ','), ']') AS attribute_values")
            ->groupBy('attributes.id')
            ->orderBy("attributes.name_$locale")
            ->get();

        return view('content.admin.catalog.product_attribute.create.index')->with([
            'product' => $product,
            'attributes' => $attributes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', $this->productAttribute);

        $productsId = $request->get('products_id');

        $this->validate($request, [
            'attribute_values_id' => ['integer', Rule::unique('product_attribute', 'attribute_values_id')->where('products_id', $productsId)],
            'products_id' => ['integer'],
        ]);


        $this->productAttribute->newQuery()->create($request->only(['products_id', 'attribute_values_id']));

        return redirect(route('admin.products.show', ['id' => $productsId]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request)
    {
        $this->authorize('delete', $this->productAttribute);

        $productsId = $request->get('products_id');
        $attributeValuesId = $request->get('attribute_values_id');

        $this->productAttribute->newQuery()->where(['products_id' => $productsId, 'attribute_values_id' => $attributeValuesId])->delete();

        return redirect(route('admin.products.show', ['id' => $productsId]));
    }
}
