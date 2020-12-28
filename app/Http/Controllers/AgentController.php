<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Agent as Requests;
use App\Models\Agent;
use Illuminate\Http\RedirectResponse;

class AgentController extends BaseItemController
{
    protected $baseUrl = '/admin/agents';

    public function list(Requests\ListRequest $request)
    {
        $items = Agent::orderBy($request->sort, $request->order);

        if ($request->name) {
            $items->where('name', 'LIKE', "%$request->name%")
                  ->orWhere('surname', 'LIKE', "%$request->name%");
        }

        if ($request->website) {
            $items->where('website', 'LIKE', "%$request->website%");
        }

        if ($request->is_retailer !== null) {
            $items->where('is_retailer', '=', $request->is_retailer);
        }

        if ($request->type !== null) {
            $items->where('type_id', '=', $request->type);
        }

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);
        $listData['types'] = Agent::types();

        return view('agent.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);
        $formData['item'] = Agent::find($request->id);
        $formData['types'] = Agent::types();

        return view('agent.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $item = Agent::firstOrNew(['id' => $request->id]);
        $item->name = $request->name;
        $item->surname = $request->surname;
        $item->website = $request->website;
        $item->is_retailer = $request->is_retailer;
        $item->type_id = (int)$request->type;
        $item->image = $request->image;
        $item->save();

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            Agent::whereIn('id', $request->items)->delete();
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
