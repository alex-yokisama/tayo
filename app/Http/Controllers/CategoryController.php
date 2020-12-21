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

        $item->attributes()->detach();

        if ($request->attribute_ids && count($request->attribute_ids) > 0) {
            if ($request->featured_attributes && count($request->featured_attributes) > 0) {
                $featured = collect($request->featured_attributes);
            } else {
                $featured = collect([]);
            }
            $attributes = Attribute::whereIn('id', $request->attribute_ids)->get();
            // dd($attributes);
            foreach ($attributes as $attribute) {
                $item->attributes()->attach(
                    $attribute,
                    ['featured' => $featured->contains($attribute->id)]);
            }
        }

        $item->save();
        $item->refresh();

        $this->updateChildAttributes($item);

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    private function updateChildAttributes($item)
    {
        foreach ($item->children as $child) {
            foreach ($item->attributes as $attribute) {
                if (!$child->attributes->contains($attribute)) {
                    $child->attributes()->attach($attribute);
                }
            }
            $child->save();
            $child->refresh();
            $this->updateChildAttributes($child);
        }
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
