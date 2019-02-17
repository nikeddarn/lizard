<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Attribute\StoreAttributeRequest;
use App\Http\Requests\Admin\Attribute\UpdateAttributeRequest;
use App\Models\Attribute;
use App\Http\Controllers\Controller;
use App\Models\AttributeValue;

class AttributeController extends Controller
{
    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var AttributeValue
     */
    private $attributeValue;

    /**
     * AttributeController constructor.
     * @param Attribute $attribute
     * @param AttributeValue $attributeValue
     */
    public function __construct(Attribute $attribute, AttributeValue $attributeValue)
    {
        $this->attribute = $attribute;
        $this->attributeValue = $attributeValue;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', $this->attribute);

        $locale = app()->getLocale();

        $attributes = $this->attribute->newQuery()->orderBy('name_' . $locale)->paginate(config('admin.show_items_per_page'));

        return view('content.admin.catalog.attribute.list.index')->with(compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->attribute);

        return view('content.admin.catalog.attribute.create.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAttributeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreAttributeRequest $request)
    {
        $this->authorize('create', $this->attribute);

        $attributeData = $request->only(['name_ru', 'name_uk']);
        $attributeData['multiply_product_values'] = (int)$request->has('multiply_product_values');
        $attributeData['indexable'] = (int)$request->has('indexable');
        $attributeData['showable'] = (int)$request->has('showable');


        $attribute = $this->attribute->newQuery()->create($attributeData);

        if ($request->has('value_ru')) {

            $valuesRu = $request->get('value_ru');
            $valuesUa = $request->get('value_uk');
            $url = $request->get('url');

            for ($i = 0; $i < count($valuesRu); $i++) {
                $attributeValue = [
                    'value_ru' => $valuesRu[$i],
                    'value_uk' => $valuesUa[$i],
                    'url' => $url[$i],
                ];

                if (isset($request->image[$i])) {
                    $attributeValue['image'] = $request->image[$i]->store('images/attributes/values', 'public');
                }

                $attribute->attributeValues()->create($attributeValue);
            }
        }

        return redirect(route('admin.attributes.index'));
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
        $this->authorize('view', $this->attribute);

        $locale = app()->getLocale();

        $attribute = $this->attribute->newQuery()->findOrFail($id);

        $attributeValues = $attribute->attributeValues()->orderBy("value_$locale")->paginate(config('admin.show_items_per_page'));

        return view('content.admin.catalog.attribute.show.index')->with([
            'attribute' => $attribute,
            'attributeValues' => $attributeValues,
            'haveValuesImages' => $attributeValues->pluck('image')->count(),
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
        $this->authorize('update', $this->attribute);

        return view('content.admin.catalog.attribute.update.index')->with([
            'attribute' => $this->attribute->newQuery()->findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAttributeRequest $request
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateAttributeRequest $request, string $id)
    {
        $this->authorize('update', $this->attribute);

        $attributeData = $request->only(['name_ru', 'name_uk']);
        $attributeData['multiply_product_values'] = (int)$request->has('multiply_product_values');
        $attributeData['indexable'] = (int)$request->has('indexable');
        $attributeData['showable'] = (int)$request->has('showable');

        $this->attribute->newQuery()->findOrFail($id)->update($attributeData);

        return redirect(route('admin.attributes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', $this->attribute);

        $this->attribute->newQuery()->findOrFail($id)->delete();

        return redirect(route('admin.attributes.index'));
    }

    /**
     * Set attribute as showable in product filters.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function makeAttributeFilterVisible(string $id)
    {
        $this->authorize('update', $this->attribute);

        $attribute = $this->attribute->newQuery()->findOrFail($id);

        $attribute->showable = 1;
        $attribute->save();

        return request()->ajax() ? '1' : back();
    }

    /**
     * Set attribute as hidden in product filters.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function makeAttributeFilterHidden(string $id)
    {
        $this->authorize('update', $this->attribute);

        $attribute = $this->attribute->newQuery()->findOrFail($id);

        $attribute->showable = 0;
        $attribute->save();

        return request()->ajax() ? '0' : back();
    }

    /**
     * Allow robots to index filter of this attribute values.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function allowRobotsIndex(string $id)
    {
        $this->authorize('update', $this->attribute);

        $attribute = $this->attribute->newQuery()->findOrFail($id);

        $attribute->indexable = 1;
        $attribute->save();

        return request()->ajax() ? '1' : back();
    }

    /**
     * Disallow robots to index filter of this attribute values.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function disallowRobotsIndex(string $id)
    {
        $this->authorize('update', $this->attribute);

        $attribute = $this->attribute->newQuery()->findOrFail($id);

        $attribute->indexable = 0;
        $attribute->save();

        return request()->ajax() ? '0' : back();
    }
}
