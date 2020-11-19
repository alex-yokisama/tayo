<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Country as Requests;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;

class CountryController extends Controller
{
    public function list(Requests\ListRequest $request)
    {
        $countries = Country::orderBy($request->sort, $request->order);

        if ($request->name) {
            $countries->where('name', 'LIKE', "%$request->name%");
        }

        return view('country.list', [
            'countries' => $countries->paginate($request->perPage),
            'backUrl' => $request->fullUrl()
        ]);
    }

    public function form(Requests\GetFormRequest $request)
    {
        return view('country.form', [
            'country' => Country::find($request->id),
            'backUrl' => $request->backUrl
        ]);
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
            Country::whereIn('id', $request->countries)->delete();
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
