<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Country as Requests;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;

class CountryController extends baseItemController
{
    protected $baseUrl = '/admin/countries';

    public function list(Requests\ListRequest $request)
    {
        $items = Country::orderBy($request->sort, $request->order);

        if ($request->name) {
            $items->where('name', 'LIKE', "%$request->name%");
        }

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);

        return view('country.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);
        $formData['item'] = Country::find($request->id);

        return view('country.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $country = Country::firstOrNew(['id' => $request->id]);
        $country->name = $request->name;
        $country->save();

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
