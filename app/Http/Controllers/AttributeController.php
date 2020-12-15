<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Attribute as Requests;
use Illuminate\Http\RedirectResponse;
use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\Measure;

class AttributeController extends BaseItemController
{
    protected $baseUrl = '/admin/attributes';

    protected function types()
    {
        return collect([
            0 => 'numeric',
            1 => 'string',
            2 => 'boolean',
            3 => 'datetime',
            4 => 'single option',
            5 => 'multiple options'
        ]);
    }

    public function list(Requests\ListRequest $request)
    {
        $items = Attribute::orderByColumn($request->sort, $request->order);

        if ($request->name) {
            $items->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->type !== null) {
            $items->where('type', '=', $request->type);
        }

        if ($request->measures && count($request->measures) > 0) {
            $items->whereHas('measure', function($q) use ($request) {
                $q->whereIn('id', $request->measures);
            });
        }

        if ($request->option) {
            $items->whereHas('option', function($q) use ($request) {
                $q->where('name', 'LIKE', "%$request->option%");
            });
        }

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);
        $listData['measures'] = Measure::all();
        $listData['types'] = $this->types();

        return view('attribute.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);
        $formData['item'] = Attribute::find($request->id);
        $formData['measures'] = Measure::all();
        $formData['types'] = $this->types();

        return view('attribute.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $attribute = Attribute::firstOrNew(['id' => $request->id]);
        $attribute->name = $request->name;

        if ($attribute->products()->count() > 0 && $attribute->type != $request->type) {
            /*
             * If attribute is used by at least one product
             * type change is not allowed
             */
             return back()->withErrors([
                 'update_type' => "Can't change type. This attribute is already used by some products."
             ])->withInput();
        } else {
            $attribute->type = $request->type;
        }

        $measure = Measure::find($request->measure);
        $attribute->measure()->associate($measure);

        $attribute->save();

        if ($attribute->type == 4 || $attribute->type == 5) {
            /*
             * Getting ids of options, that exist for this attribute
             * but are not present in the request data. They are
             * candidates for deletion
             */
            $option_ids = $attribute->options->map(function($item) {
                return $item->id;
            });

            if ($option_ids->count() > 0) {
                $option_ids = $option_ids->diff(collect($request->options)->map(function($item) {
                    return $item['id'];
                }));

                try {
                    AttributeOption::whereIn('id', $option_ids)->delete();
                } catch (\Illuminate\Database\QueryException $ex) {
                    return back()->withErrors([
                        'delete' => "Unable to delete options. They are already used in some products."
                    ])->withInput();
                }
            }

            foreach ($request->options as $option) {
                $attributeOption = AttributeOption::firstOrNew(['id' => $option['id']]);
                $attributeOption->name = $option['name'];
                $attribute->options()->save($attributeOption);
            }
        }

        $attribute->save();

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
