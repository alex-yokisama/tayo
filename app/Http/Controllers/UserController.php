<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User as Requests;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class UserController extends BaseItemController
{
    protected $baseUrl = '/admin/users';

    public function list(Requests\ListRequest $request)
    {
        $items = User::with('roles');

        if ($request->name) {
            $items->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->email) {
            $items->where('email', 'LIKE', "%$request->email%");
        }

        if ($request->roles && count($request->roles) > 0) {
            $items->whereHas('roles', function($q) use ($request) {
                $q->whereIn('id', $request->roles);
            });
        }

        $items->orderBy($request->sort, $request->order);

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);
        $listData['roles'] = Role::all();

        return view('user.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $user = User::find($request->id);
        if (!$user) {
            return redirect($request->backUrl)->withErrors(['404', 'user not found']);
        }

        $formData = $this->getFormData($request);
        $formData['item'] = $user;
        $formData['roles'] = Role::all();

        return view('user.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return redirect($request->backUrl)->withErrors(['404', 'user not found']);
        }

        $roles = Role::whereIn('id', $request->roles)->get();
        $user->syncRoles($roles);

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }
}
