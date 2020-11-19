<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Measure as Requests;
use App\Models\Measure;
use Illuminate\Http\RedirectResponse;

class MeasureController extends Controller
{
    public function list(Requests\ListRequest $request)
    {
        $measures = Measure::orderBy($request->sort, $request->order);

        if ($request->name) {
            $measures->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->short_name) {
            $measures->where('short_name', 'LIKE', "%$request->short_name%");
        }

        return view('measure.list', [
            'measures' => $measures->paginate($request->perPage),
            'backUrl' => $request->fullUrl()
        ]);
    }

    public function form(Requests\GetFormRequest $request)
    {
        return view('measure.form', [
            'measure' => Measure::find($request->id),
            'backUrl' => $request->backUrl
        ]);
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
            Measure::whereIn('id', $request->measures)->delete();
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
