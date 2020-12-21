<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AttributeGroup as Requests;
use App\Models\AttributeGroup;
use Illuminate\Http\RedirectResponse;

class AttributeGroupController extends baseItemController
{
    protected $baseUrl = '/admin/attribute_groups';

    public function list(Requests\ListRequest $request)
    {
        $items = AttributeGroup::orderBy($request->sort, $request->order);

        if ($request->name) {
            $items->where('name', 'LIKE', "%$request->name%");
        }

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);

        return view('attribute_group.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);
        $formData['item'] = AttributeGroup::find($request->id);

        return view('attribute_group.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $item = AttributeGroup::firstOrNew(['id' => $request->id]);
        $item->name = $request->name;
        $item->sort_order = $request->sort_order;
        $item->save();

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            AttributeGroup::whereIn('id', $request->items)->delete();
        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->withErrors([
                'delete' => 'Unable to delete. Selected items are used in other objects.'
            ])->withInput();
        }

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'deleted successfully'
        ]);
    }
}
