<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Role as Requests;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function list(Requests\ListRequest $request)
    {
        $roles = Role::whereNotIn('id', [1]);

        if ($request->name) {
            $roles->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->permissions && count($request->permissions) > 0) {
            $roles->whereHas('permissions', function($q) use ($request) {
                $q->whereIn('id', $request->permissions);
            });
        }

        $roles->orderBy($request->sort, $request->order);

        return view('role.list', [
            'roles' => $roles->paginate($request->perPage),
            'backUrl' => $request->fullUrl()
        ]);
    }

    public function form(Requests\GetFormRequest $request)
    {
        return view('role.form', [
            'role' => Role::find($request->id),
            'permissions' => Permission::all(),
            'backUrl' => $request->backUrl
        ]);
    }

    public function save(Requests\SaveRequest $request)
    {
        $role = Role::firstOrNew(['id' => $request->id]);
        $role->name = $request->name;
        $role->save();

        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        Role::whereIn('id', $request->roles)->delete();
        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'deleted successfully'
        ]);
    }
}
