<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AgeRating as Requests;
use App\Models\AgeRating;
use Illuminate\Http\RedirectResponse;

class AgeRatingController extends baseItemController
{
    protected $baseUrl = '/admin/age_ratings';

    public function list(Requests\ListRequest $request)
    {
        $items = AgeRating::orderBy($request->sort, $request->order);

        if ($request->name) {
            $items->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->age_from) {
            $items->where('age_from', '=', $request->age_from);
        }

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);

        return view('age_rating.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);
        $formData['item'] = AgeRating::find($request->id);

        return view('age_rating.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $item = AgeRating::firstOrNew(['id' => $request->id]);
        $item->name = $request->name;
        $item->age_from = $request->age_from;
        $item->save();

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            Country::whereIn('id', $request->items)->delete();
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
