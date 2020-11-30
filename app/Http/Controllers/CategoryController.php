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
        $items = Category::doesntHave('parent');

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
        $formData['categories'] = $this->listWithFullPath(Category::doesntHave('parent')->get(), $request->id ? $request->id : 0);
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

        if ($request->attribute_ids && count($request->attribute_ids) > 0) {
            foreach ($request->attribute_ids as $attribute_id) {
                $attribute = Attribute::find($attribute_id);
                if ($attribute) {
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

    protected function listWithFullPath($categories, $excludeId = 0)
    {
        $list = collect([]);
        foreach ($categories as $category) {
            $item = (object)['id' => $category->id, 'name' => $category->name];
            if ($category->id != $excludeId) {
                $list->push($item);

                if ($category->children) {
                    $list = $list->concat($this->listWithFullPath($category->children, $excludeId)->map(function($val) use ($item) {
                        $val->name = $item->name.' > '.$val->name;
                        return $val;
                    }));
                }
            }
        }
        return $list;
    }
}
