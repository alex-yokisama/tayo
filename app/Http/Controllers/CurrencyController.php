<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Currency as Requests;
use Illuminate\Http\RedirectResponse;
use App\Models\Currency;
use App\Models\Country;

class CurrencyController extends BaseItemController
{
    protected $baseUrl = '/admin/currencies';

    public function list(Requests\ListRequest $request)
    {
        $items = Currency::orderByColumn($request->sort, $request->order);

        if ($request->name) {
            $items->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->symbol) {
            $items->where('symbol', 'LIKE', "%$request->symbol%");
        }

        if ($request->countries && count($request->countries) > 0) {
            $items->whereHas('country', function($q) use ($request) {
                $q->whereIn('id', $request->countries);
            });
        }

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);
        $listData['countries'] = Country::all();

        return view('currency.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);
        $formData['item'] = Currency::find($request->id);
        $formData['countries'] = Country::all();

        return view('currency.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $currency = Currency::firstOrNew(['id' => $request->id]);
        $currency->name = $request->name;
        $currency->symbol = $request->symbol;

        try {
            $country = Country::findOrFail($request->country);
            $currency->country()->associate($country);
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return back()->withErrors([
                'country' => 'Selected country does not exost'
            ])->withInput();
        }

        $currency->save();

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            Currency::whereIn('id', $request->items)->delete();
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
