<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Filter\StoreFilterRequest;
use App\Models\Filter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

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
     */
    public function index()
    {
        if (Gate::denies('local-catalog-show', auth('web')->user())) {
            abort(401);
        }

        return view('content.admin.catalog.filter.list.index')->with([
            'filters' => $this->filter->newQuery()->paginate(config('admin.show_items_per_page')),
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

        return view('content.admin.catalog.filter.create.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFilterRequest $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $this->filter->newQuery()->create($request->only(['name_ru', 'name_uk']));

        return redirect(route('admin.filters.index'));
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
     */
    public function edit(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

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
     */
    public function update(StoreFilterRequest $request, string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $this->filter->newQuery()->findOrFail($id)->update($request->only(['name_ru', 'name_uk']));

        return redirect(route('admin.filters.index'));
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

        $this->filter->newQuery()->findOrFail($id)->delete();

        return redirect(route('admin.filters.index'));
    }
}
