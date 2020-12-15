<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Brand as Requests;
use Illuminate\Http\RedirectResponse;
use App\Models\Brand;
use App\Models\BrandContact;
use App\Models\Country;

class BrandController extends BaseItemController
{
    protected $baseUrl = '/admin/brands';

    public function list(Requests\ListRequest $request)
    {
        $items = Brand::orderByColumn($request->sort, $request->order);

        if ($request->name) {
            $items->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->website) {
            $items->where('website', 'LIKE', "%$request->website%");
        }

        if ($request->countries && count($request->countries) > 0) {
            $items->whereHas('country', function($q) use ($request) {
                $q->whereIn('id', $request->countries);
            });
        }

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);
        $listData['countries'] = Country::all();

        return view('brand.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);
        $formData['item'] = Brand::find($request->id);
        $formData['countries'] = Country::all();

        return view('brand.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $brand = Brand::firstOrNew(['id' => $request->id]);
        $brand->name = $request->name;
        $brand->website = $request->website;
        $brand->bio = $request->bio;
        $brand->image = $request->image;

        try {
            $country = Country::findOrFail($request->country);
            $brand->country()->associate($country);
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return back()->withErrors([
                'country' => 'Selected country does not exost'
            ])->withInput();
        }

        $brand->save();

        $brand->contacts()->delete();

        if ($request->contacts) {
            foreach ($request->contacts as $contact) {
                $brand->contacts()->save(new BrandContact($contact));
            }
        }

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            Brand::whereIn('id', $request->items)->delete();
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
