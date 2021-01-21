<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\App as Requests;
use Illuminate\Http\RedirectResponse;
use App\Models\App;
use App\Models\AppImage;
use App\Models\AppLink;
use App\Models\OS;
use App\Models\Country;
use App\Models\Brand;


class AppController extends BaseItemController
{
    protected $baseUrl = '/admin/apps';

    public function list(Requests\ListRequest $request)
    {
        $items = App::orderBy($request->sort, $request->order)->with(['os', 'brand', 'countries', 'links']);

        if ($request->name) {
            $items->where('app.name', 'LIKE', '%'.$request->name.'%');
        }

        if ($request->type !== null) {
            $items->where('type_id', '=', $request->type);
        }

        if ($request->price_from) {
            $items->where('price', '>=', $request->price_from);
        }

        if ($request->price_to) {
            $items->where('price', '<=', $request->price_to);
        }

        if ($request->brand) {
            $items->whereHas('brand', function($q) use ($request) {
                $q->where('brand.name', 'LIKE', '%'.$request->brand.'%');
            });
        }

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);
        $listData['types'] = App::types();

        return view('app.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);
        $formData['item'] = App::find($request->id);
        $formData['types'] = App::types();
        $formData['brands'] = Brand::all();

        return view('app.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $item = App::firstOrNew(['id' => $request->id]);

        // general
        $item->name = $request->name;
        $item->price = $request->price;
        $item->change_log_url = $request->change_log_url;
        $item->type_id = $request->type;
        $item->save();

        // images
        $item->images()->delete();

        if ($request->images) {
            foreach ($request->images as $order => $path) {
                $item->images()->save(new AppImage(['path' => $path, 'order' => $order]));
            }
        }

        // relations
        try {
            $brand = Brand::findOrFail($request->brand);
            $item->brand()->associate($brand);
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return back()->withErrors([
                'brand' => 'Selected brand does not exist'
            ])->withInput();
        }

        $item->os()->detach();
        if ($request->os) {
            $osList = OS::whereKey($request->os)->get();
            foreach ($osList as $os) {
                $item->os()->attach($os);
            }
        }

        $item->countries()->detach();
        if ($request->countries) {
            $countries = Country::whereKey($request->countries)->get();
            foreach ($countries as $country) {
                $item->countries()->attach($country);
            }
        }

        // links
        $item->links()->delete();
        if (is_array($request->links)) {
            foreach ($request->links as $linkArr) {
                $link = (object)$linkArr;
                if (OS::find($link->os) == null) {
                    continue;
                }
                $item->links()->save(new AppLink([
                    'os_id' => $link->os,
                    'app_store_name' => $link->app_store_name,
                    'price' => $link->price,
                    'url' => $link->url,
                ]));
            }
        }

        $item->save();

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            App::whereKey($request->items)->delete();
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
