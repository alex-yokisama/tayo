<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Brand as Requests;
use Illuminate\Http\RedirectResponse;
use App\Models\Brand;
use App\Models\Country;

class BrandController extends Controller
{
    public function list(Requests\ListRequest $request)
    {
        $brands = Brand::orderByColumn($request->sort, $request->order);

        if ($request->name) {
            $brands->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->countries && count($request->countries) > 0) {
            $brands->whereHas('country', function($q) use ($request) {
                $q->whereIn('id', $request->countries);
            });
        }

        return view('brand.list', [
            'brands' => $brands->paginate($request->perPage),
            'backUrl' => $request->fullUrl()
        ]);
    }

    public function form(Requests\GetFormRequest $request)
    {
        return view('brand.form', [
            'brand' => Brand::find($request->id),
            'countries' => Country::all(),
            'backUrl' => $request->backUrl
        ]);
    }

    public function save(Requests\SaveRequest $request)
    {
        $brand = Brand::firstOrNew(['id' => $request->id]);
        $brand->name = $request->name;
        $brand->website = $request->website;
        $brand->bio = $request->bio;

        try {
            $country = Country::findOrFail($request->country);
            $brand->country()->associate($country);
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return back()->withErrors([
                'country' => 'Selected country does not exost'
            ])->withInput();
        }

        $brand->save();

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            Brand::whereIn('id', $request->brands)->delete();
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
