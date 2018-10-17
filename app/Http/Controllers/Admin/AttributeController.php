<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreAttributeRequest;
use App\Models\Attribute;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    /**
     * @var Attribute
     */
    private $attribute;

    /**
     * AttributeController constructor.
     * @param Attribute $attribute
     */
    public function __construct(Attribute $attribute)
    {
        $this->attribute = $attribute;
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

        return view('content.admin.catalog.attribute.list.index')->with([
            'attributes' => $this->attribute->newQuery()->paginate(config('admin.show_items_per_page')),
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

        $attribute = $this->attribute->newQuery()->create($request->only(['name_ru', 'name_ua']));

        if ($request->has('value_ru')){

            $valuesRu = $request->get('value_ru');
            $valuesUa = $request->get('value_ua');

            for ($i = 0; $i < count($valuesRu); $i++){
                $attributeValue = [
                    'value_ru' => $valuesRu[$i],
                    'value_ua' => $valuesUa[$i],
                ];

                if (isset($request->image[$i])){
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

        $attribute = $this->attribute->newQuery()->findOrFail($id);

        $attributeValues = $attribute->attributeValues()->paginate(config('admin.show_items_per_page'));

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
     * @param StoreAttributeRequest $request
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(StoreAttributeRequest $request, string $id)
    {
        $this->authorize('update', $this->attribute);

        $this->attribute->newQuery()->findOrFail($id)->update($request->only(['name_ru', 'name_ua']));

        return redirect(route('admin.attributes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', $this->attribute);

        $this->attribute->newQuery()->findOrFail($id)->delete();

        return redirect(route('admin.attributes.index'));
    }
}
