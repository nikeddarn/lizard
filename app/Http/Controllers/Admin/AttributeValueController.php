<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AttributeValue\StoreAttributeValueRequest;
use App\Http\Requests\Admin\AttributeValue\UpdateAttributeValueRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class AttributeValueController extends Controller
{
    /**
     * @var AttributeValue
     */
    private $attributeValue;
    /**
     * @var Attribute
     */
    private $attribute;

    /**
     * AttributeValueController constructor.
     * @param Attribute $attribute
     * @param AttributeValue $attributeValue
     */
    public function __construct(Attribute $attribute, AttributeValue $attributeValue)
    {

        $this->attributeValue = $attributeValue;
        $this->attribute = $attribute;
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

        return view('content.admin.catalog.attribute_value.create.index')->with([
            'attribute' => $this->attribute->newQuery()->findOrFail($id),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAttributeValueRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttributeValueRequest $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $attributeId = $request->get('attributeId');

        $attributeValueData = $request->only(['value_ru', 'value_uk', 'url']);

        $attributeValueData['attributes_id'] = $attributeId;

        if($request->has('image')){
            $attributeValueData['image'] = $request->image->store('images/attributes/values', 'public');
        }

        $this->attributeValue->newQuery()->create($attributeValueData);

        return redirect(route('admin.attributes.show', ['id' => $attributeId]));
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

        return view('content.admin.catalog.attribute_value.update.index')->with([
            'attributeValue' => $this->attributeValue->newQuery()->findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAttributeValueRequest $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAttributeValueRequest $request, string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $attributeValue = $this->attributeValue->newQuery()->findOrFail($id);

        $attributeValueData = $request->only('value_ru', 'value_uk', 'url');

        if ($request->has('image')){
            $attributeValueData['image'] = $request->image->store('images/attributes/values', 'public');
        }elseif ($request->get('delete-image') === '1'){
            $attributeValueData['image'] = null;
            Storage::disk('public')->delete($attributeValue->image);
        }

        $attributeValue->update($attributeValueData);

        return redirect(route('admin.attributes.show', ['id' => $attributeValue->attributes_id]));
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

        $this->attributeValue->newQuery()->findOrFail($id)->delete();

        return back();
    }
}
