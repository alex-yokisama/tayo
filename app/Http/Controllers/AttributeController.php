<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Attribute as Requests;
use Illuminate\Http\RedirectResponse;
use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\Measure;

class AttributeController extends Controller
{
    public function list(Requests\ListRequest $request)
    {
        $attributes = Attribute::orderByColumn($request->sort, $request->order);

        if ($request->name) {
            $attributes->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->type) {
            $attributes->where('type', '=', $request->type);
        }

        if ($request->measures && count($request->measures) > 0) {
            $attributes->whereHas('measure', function($q) use ($request) {
                $q->whereIn('id', $request->measures);
            });
        }

        if ($request->option) {
            $attributes->whereHas('option', function($q) use ($request) {
                $q->where('name', 'LIKE', "%$request->option%");
            });
        }

        return view('attribute.list', [
            'attributes' => $attributes->paginate($request->perPage),
            'backUrl' => $request->fullUrl()
        ]);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $types = collect([
            0 => 'numeric',
            1 => 'string',
            2 => 'boolean',
            3 => 'datetime',
            4 => 'single option',
            5 => 'multiple options'
        ]);
        return view('attribute.form', [
            'attribute' => Attribute::find($request->id),
            'measures' => Measure::all(),
            'types' => $types,
            'backUrl' => $request->backUrl
        ]);
    }

    public function save(Requests\SaveRequest $request)
    {
        $attribute = Attribute::firstOrNew(['id' => $request->id]);
        $attribute->name = $request->name;
        $attribute->type = $request->type;

        $measure = Measure::find($request->measure);
        if ($measure) {
            $attribute->measure()->associate($measure);
        }

        $attribute->save();

        if ($attribute->type == 4 || $attribute->type == 5) {
            foreach ($request->options as $option) {
                $attribute->options()->create(['name' => $option]);
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
            Attribute::whereIn('id', $request->items)->delete();
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
