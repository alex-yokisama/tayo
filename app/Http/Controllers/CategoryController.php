<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Category as Requests;
use Illuminate\Http\RedirectResponse;
use App\Models\Category;
use App\Models\Attribute;
use App\Services\SidebarLinksService;

class CategoryController extends BaseItemController
{
    protected $baseUrl = '/admin/categories';

    public function list(Requests\ListRequest $request)
    {
        $items = Category::doesntHave('parent')->orderBy('name', 'ASC');

        return view('category.tree', [
            'items' => $items->get(),
            'backUrl' => $request->fullUrl(),
            'sidebarLinks' => SidebarLinksService::getLinks($this->baseUrl)
        ]);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);
        $formData['item'] = Category::find($request->id);
        $formData['categories'] = Category::listWithFullPath($request->id ? $request->id : 0);
        $formData['attributes'] = Attribute::all();
        $formData['parent'] = $request->parent;

        return view('category.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $item = Category::firstOrNew(['id' => $request->id]);
        $item->name = $request->name;

        $item->parent()->dissociate();
        $parent = Category::find($request->parent);
        if ($parent) {
            $item->parent()->associate($parent);
        }

        $item->save();
        $item->refresh();

        $item->attributes()->detach();

        if ($parent) {
            foreach ($parent->attributes as $attribute) {
                $item->attributes()->attach($attribute);
            }
        }

        if ($request->attribute_ids && count($request->attribute_ids) > 0) {
            foreach ($request->attribute_ids as $attribute_id) {
                $attribute = Attribute::find($attribute_id);
                if ($attribute ) {
                    $item->attributes()->attach($attribute);
                }
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
