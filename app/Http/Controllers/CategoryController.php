<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Category as Requests;
use Illuminate\Http\RedirectResponse;
use App\Models\Category;
use App\Models\Attribute;

class CategoryController extends Controller
{
    public function list(Requests\ListRequest $request)
    {
        $items = Category::orderByColumn($request->sort, $request->order);

        if ($request->name) {
            $items->where('name', 'LIKE', "%$request->name%");
        }

        if ($request->parent) {
            $items->whereHas('parent', function($q) use ($request) {
                $q->where('name', 'LIKE', "%$request->parent%");
            });
        }

        return view('category.list', [
            'items' => $items->paginate($request->perPage),
            'backUrl' => $request->fullUrl()
        ]);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $categories = Category::select();
        if ($request->id) {
            $categories->whereNotIn('id', [$request->id]);
        }

        return view('category.form', [
            'item' => Category::find($request->id),
            'categories' => $categories->get(),
            'attributes' => Attribute::all(),
            'parent' => Category::find($request->parent),
            'backUrl' => $request->backUrl
        ]);
    }

    public function save(Requests\SaveRequest $request)
    {
        $item = Category::firstOrNew(['id' => $request->id]);
        $item->name = $request->name;

        $parent = Category::find($request->parent);
        if ($parent) {
            $item->parent()->associate($parent);
        }

        $item->attributes()->detach();

        foreach ($request->attribute_ids as $attribute_id) {
            $attribute = Attribute::find($attribute_id);
            if ($attribute) {
                $item->attributes()->attach($attribute);
            }
        }

        $item->save();

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            Category::whereIn('id', $request->items)->delete();
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
