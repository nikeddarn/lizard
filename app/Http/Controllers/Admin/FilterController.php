<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Filter\StoreFilterRequest;
use App\Models\Filter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FilterController extends Controller
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * FilterController constructor.
     * @param Filter $filter
     */
    public function __construct(Filter $filter)
    {

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
        $this->authorize('view', $this->filter);

        return view('content.admin.catalog.filter.list.index')->with([
            'filters' => $this->filter->newQuery()->paginate(config('admin.show_items_per_page')),
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
        $this->authorize('create', $this->filter);

        return view('content.admin.catalog.filter.create.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFilterRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreFilterRequest $request)
    {
        $this->authorize('create', $this->filter);

        $this->filter->newQuery()->create($request->only(['name_ru', 'name_uk']));

        return redirect(route('admin.filters.index'));
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
        $this->authorize('view', $this->filter);

        $filter = $this->filter->newQuery()->findOrFail($id);

        $filterProducts = $filter->products()->paginate(config('admin.show_items_per_page'));

        return view('content.admin.catalog.filter.show.index')->with([
            'filter' => $filter,
            'filterProducts' => $filterProducts,
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
        $this->authorize('update', $this->filter);

        return view('content.admin.catalog.filter.update.index')->with([
            'filter' => $this->filter->newQuery()->findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreFilterRequest $request
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(StoreFilterRequest $request, string $id)
    {
        $this->authorize('update', $this->filter);

        $this->filter->newQuery()->findOrFail($id)->update($request->only(['name_ru', 'name_uk']));

        return redirect(route('admin.filters.index'));
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
        $this->authorize('delete', $this->filter);

        $this->filter->newQuery()->findOrFail($id)->delete();

        return redirect(route('admin.filters.index'));
    }
}
