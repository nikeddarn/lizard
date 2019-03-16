<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Attribute\StoreAttributeRequest;
use App\Http\Requests\Admin\Attribute\UpdateAttributeRequest;
use App\Models\Attribute;
use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\Gate;

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
     */
    public function index()
    {
        if (Gate::denies('local-catalog-show', auth('web')->user())) {
            abort(401);
        }

        $locale = app()->getLocale();

        $attributes = $this->attribute->newQuery()->orderBy('name_' . $locale)->paginate(config('admin.show_items_per_page'));

        return view('content.admin.catalog.attribute.list.index')->with(compact('attributes'));
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

        return view('content.admin.catalog.attribute.create.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAttributeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreAttributeRequest $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

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
     */
    public function show(string $id)
    {
        if (Gate::denies('local-catalog-show', auth('web')->user())) {
            abort(401);
        }

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
     */
    public function edit(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

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
     */
    public function update(UpdateAttributeRequest $request, string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

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
     * @throws \Exception
     */
    public function destroy(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $this->attribute->newQuery()->findOrFail($id)->delete();

        return redirect(route('admin.attributes.index'));
    }

    /**
     * Set attribute as showable in product filters.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function makeAttributeFilterVisible(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

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
     */
    public function makeAttributeFilterHidden(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

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
     */
    public function allowRobotsIndex(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

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
     */
    public function disallowRobotsIndex(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $attribute = $this->attribute->newQuery()->findOrFail($id);

        $attribute->indexable = 0;
        $attribute->save();

        return request()->ajax() ? '0' : back();
    }
}
