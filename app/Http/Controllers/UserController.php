<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User as Requests;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function list(Requests\ListRequest $request)
    {
        $users = User::with('roles');

        if ($request->name) {
            $users->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->email) {
            $users->where('email', 'LIKE', "%$request->email%");
        }

        if ($request->roles && count($request->roles) > 0) {
            $users->whereHas('roles', function($q) use ($request) {
                $q->whereIn('id', $request->roles);
            });
        }

        $users->orderBy($request->sort, $request->order);

        return view('user.list', [
            'users' => $users->get(),
            'backUrl' => $request->fullUrl()
        ]);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $user = User::find($request->id);
        if (!$user) {
            return redirect($request->backUrl)->withErrors(['404', 'user not found']);
        }
        return view('user.form', [
            'user' => $user,
            'roles' => Role::all(),
            'backUrl' => $request->backUrl
        ]);
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
