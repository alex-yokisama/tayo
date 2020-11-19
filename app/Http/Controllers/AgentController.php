<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Agent as Requests;
use App\Models\Agent;
use Illuminate\Http\RedirectResponse;

class AgentController extends Controller
{
    public function list(Requests\ListRequest $request)
    {
        $agents = Agent::orderBy($request->sort, $request->order);

        if ($request->name) {
            $agents->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->is_retailer !== null) {
            $agents->where('is_retailer', '=', $request->is_retailer);
        }

        return view('agent.list', [
            'agents' => $agents->paginate($request->perPage),
            'backUrl' => $request->fullUrl()
        ]);
    }

    public function form(Requests\GetFormRequest $request)
    {
        return view('agent.form', [
            'agent' => Agent::find($request->id),
            'backUrl' => $request->backUrl
        ]);
    }

    public function save(Requests\SaveRequest $request)
    {
        $agent = Agent::firstOrNew(['id' => $request->id]);
        $agent->name = $request->name;
        $agent->website = $request->website;
        $agent->is_retailer = $request->is_retailer;
        $agent->save();

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            Agent::whereIn('id', $request->agents)->delete();
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
