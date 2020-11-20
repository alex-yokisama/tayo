<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Currency as Requests;
use Illuminate\Http\RedirectResponse;
use App\Models\Currency;
use App\Models\Country;

class CurrencyController extends Controller
{
    public function list(Requests\ListRequest $request)
    {
        $currencies = Currency::orderByColumn($request->sort, $request->order);

        if ($request->name) {
            $currencies->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->symbol) {
            $currencies->where('symbol', 'LIKE', "%$request->symbol%");
        }

        if ($request->countries && count($request->countries) > 0) {
            $currencies->whereHas('country', function($q) use ($request) {
                $q->whereIn('id', $request->countries);
            });
        }

        return view('currency.list', [
            'currencies' => $currencies->paginate($request->perPage),
            'backUrl' => $request->fullUrl()
        ]);
    }

    public function form(Requests\GetFormRequest $request)
    {
        return view('currency.form', [
            'currency' => Currency::find($request->id),
            'countries' => Country::all(),
            'backUrl' => $request->backUrl
        ]);
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
            Currency::whereIn('id', $request->currencies)->delete();
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
