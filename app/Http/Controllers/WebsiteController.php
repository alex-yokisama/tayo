<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Website as Requests;
use App\Models\Website;
use Illuminate\Http\RedirectResponse;

class WebsiteController extends BaseItemController
{
    protected $baseUrl = '/admin/websites';

    public function list(Requests\ListRequest $request)
    {
        $items = Website::orderBy($request->sort, $request->order);

        if ($request->name) {
            $items->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->url) {
            $items->where('url', 'LIKE', "%$request->url%");
        }

        if ($request->description) {
            $items->where('description', 'LIKE', "%$request->description%");
        }

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);

        return view('website.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);
        $formData['item'] = Website::find($request->id);

        return view('website.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $item = Website::firstOrNew(['id' => $request->id]);
        $item->name = $request->name;
        $item->url = $request->url;
        $item->description = $request->description;
        $item->logo = $request->logo;
        $item->save();

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            Website::whereIn('id', $request->items)->delete();
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
