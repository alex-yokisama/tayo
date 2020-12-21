<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\License as Requests;
use App\Models\LicenseType;
use Illuminate\Http\RedirectResponse;

class LicenseController extends BaseItemController
{
    protected $baseUrl = '/admin/licenses';

    public function list(Requests\ListRequest $request)
    {
        $items = LicenseType::orderBy($request->sort, $request->order);

        if ($request->name) {
            $items->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->is_open_source !== null) {
            $items->where('is_open_source', '=', $request->is_open_source);
        }

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);

        return view('license.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);
        $formData['item'] = LicenseType::find($request->id);

        return view('license.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $item = LicenseType::firstOrNew(['id' => $request->id]);
        $item->name = $request->name;
        $item->is_open_source = $request->is_open_source;
        $item->save();

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            LicenseType::whereIn('id', $request->items)->delete();
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
