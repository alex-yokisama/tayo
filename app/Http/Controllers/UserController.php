<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User as Requests;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

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

        $formData = $this->getFormData($request);
        $formData['item'] = $user;
        $formData['roles'] = Role::orderByRaw('CASE WHEN name="admin" THEN 0 ELSE 1 END ASC')->orderBy('name', 'ASC')->get();

        return view('user.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        } else {
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
        }

        $user->save();

        $roles = Role::whereIn('id', $request->roles)->get();
        $user->syncRoles($roles);

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        $userIds = collect($request->items)->filter(function($item) use ($request) {
            return $item != $request->user()->id;
        });

        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            $user->deleteProfilePhoto();
            $user->tokens->each->delete();
            $user->delete();
        }

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'deleted successfully'
        ]);
    }
}
