<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Measure as Requests;
use App\Models\Measure;
use Illuminate\Http\RedirectResponse;

class MeasureController extends BaseItemController
{
    protected $baseUrl = '/admin/measures';

    public function list(Requests\ListRequest $request)
    {
        $items = Measure::orderBy($request->sort, $request->order);

        if ($request->name) {
            $items->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->short_name) {
            $items->where('short_name', 'LIKE', "%$request->short_name%");
        }

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);

        return view('measure.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);
        $formData['item'] = Measure::find($request->id);

        return view('measure.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $measure = Measure::firstOrNew(['id' => $request->id]);
        $measure->name = $request->name;
        $measure->short_name = $request->short_name;
        $measure->save();

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            Measure::whereIn('id', $request->items)->delete();
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
