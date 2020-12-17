<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Product as Requests;
use Illuminate\Http\RedirectResponse;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Country;
use App\Models\ProductPriceChange;
use App\Models\Attribute;
use App\Models\ProductLink;
use App\Models\ProductImage;
use App\Models\ProductContent;
use App\Models\Currency;
use App\Models\Agent;
use App\Models\Website;

class ProductController extends BaseItemController
{
    protected $baseUrl = '/admin/products';

    public function list(Requests\ListRequest $request)
    {
        $items = Product::orderByColumn($request->sort, $request->order)->with(['category', 'brand', 'country']);

        if ($request->name) {
            $items->where('product.name', 'LIKE', '%'.$request->name.'%')
                ->orWhere('sku', 'LIKE', '%'.$request->name.'%')
                ->orWhere('model', 'LIKE', '%'.$request->name.'%')
                ->orWhere('model_family', 'LIKE', '%'.$request->name.'%');
        }

        if ($request->text) {
            $items->where('excerpt', 'LIKE', '%'.$request->text.'%')
                ->orWhere('summary_main', 'LIKE', '%'.$request->text.'%')
                ->orWhere('summary_value', 'LIKE', '%'.$request->text.'%')
                ->orWhere('full_overview', 'LIKE', '%'.$request->text.'%')
                ->orWhere('seo_keywords', 'LIKE', '%'.$request->text.'%')
                ->orWhere('tags', 'LIKE', '%'.$request->text.'%');
        }

        if ($request->price_from) {
            $items->where('price_current', '>=', $request->price_from)
                ->orWhere('price_msrp', '>=', $request->price_from);
        }

        if ($request->price_to) {
            $items->where('price_current', '<=', $request->price_to)
                ->orWhere('price_msrp', '<=', $request->price_to);
        }

        if ($request->created_at_from) {
            $items->where('product.created_at', '>=', $request->created_at_from);
        }

        if ($request->date_publish_from) {
            $items->where('date_publish', '>=', $request->date_publish_from);
        }

        if ($request->created_at_to) {
            $items->where('product.created_at', '<=', $request->created_at_to);
        }

        if ($request->date_publish_to) {
            $items->where('date_publish', '<=', $request->date_publish_to);
        }

        if ($request->countries && count($request->countries) > 0) {
            $items->whereHas('country', function($q) use ($request) {
                $q->whereIn('id', $request->countries);
            });
        }

        if ($request->brands && count($request->brands) > 0) {
            $items->whereHas('brand', function($q) use ($request) {
                $q->whereIn('id', $request->brands);
            });
        }

        if ($request->categories && count($request->categories) > 0) {
            $items->whereHas('category', function($q) use ($request) {
                $q->whereIn('id', $request->categories);
            });
        }

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);
        $listData['categories'] = Category::all();
        $listData['countries'] = Country::all();
        $listData['brands'] = Brand::all();

        return view('product.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);

        if ($request->copy_id) {
            $formData['item'] = Product::findOrFail($request->copy_id);
            $formData['is_copy'] = true;
        } else {
            $formData['item'] = Product::find($request->id);
        }

        $formData['brands'] = Brand::all();
        $formData['countries'] = Country::all();
        $formData['currencies'] = Currency::all();
        $formData['categories'] = Category::listWithFullPath();
        $formData['websites'] = Website::all();
        $formData['contentTypes'] = ProductContent::allowedTypes();

        return view('product.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $item = Product::firstOrNew(['id' => $request->id]);
        $oldPrice = $item->price_current;
        $oldPriceCurrency = $item->currentCurrency;
        $oldMsrp = $item->price_msrp;
        $oldMsrpCurrency = $item->msrpCurrency;

        //general
        $item->name = $request->name;
        $item->sku = $request->sku;
        $item->model = $request->model;
        $item->model_family = $request->model_family;
        $item->price_msrp = $request->price_msrp;
        $item->price_current = $request->price_current;
        $item->size_length = $request->size_length;
        $item->size_width = $request->size_width;
        $item->size_height = $request->size_height;
        $item->weight = $request->weight;
        $item->date_publish = $request->date_publish;
        $item->is_promote = !!$request->is_promote;

        //description
        $item->excerpt = $request->excerpt;
        $item->summary_main = $request->summary_main;
        $item->summary_value = $request->summary_value;
        $item->full_overview = $request->full_overview;
        $item->seo_keywords = $request->seo_keywords;
        if ($request->tags !== null && count($request->tags) > 0) {
            $item->tags = implode(',', $request->tags);
        } else {
            $item->tags = null;
        }

        $item->save();

        //images
        $item->images()->delete();

        if ($request->images) {
            foreach ($request->images as $order => $path) {
                $item->images()->save(new ProductImage(['path' => $path, 'order' => $order]));
            }
        }

        //contents
        $item->contents()->delete();

        if ($request->contents) {
            foreach ($request->contents as $content) {
                $item->contents()->save(new ProductContent($content));
            }
        }

        //relations
        try {
            $currency = Currency::findOrFail($request->currency_msrp);
            $item->msrpCurrency()->associate($currency);
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return back()->withErrors([
                'currency_msrp' => 'Selected currency does not exist'
            ])->withInput();
        }

        try {
            $currency = Currency::findOrFail($request->currency_current);
            $item->currentCurrency()->associate($currency);
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return back()->withErrors([
                'currency_current' => 'Selected currency does not exist'
            ])->withInput();
        }

        try {
            $category = Category::findOrFail($request->category);
            $item->category()->associate($category);
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return back()->withErrors([
                'category' => 'Selected category does not exist'
            ])->withInput();
        }

        try {
            $country = Country::findOrFail($request->country);
            $item->country()->associate($country);
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return back()->withErrors([
                'country' => 'Selected country does not exist'
            ])->withInput();
        }

        try {
            $brand = Brand::findOrFail($request->brand);
            $item->brand()->associate($brand);
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return back()->withErrors([
                'brand' => 'Selected brand does not exist'
            ])->withInput();
        }

        $item->targetCountries()->detach();
        if ($request->countries) {
            $countries = Country::whereIn('id', $request->countries)->get();
            foreach ($countries as $country) {
                $item->targetCountries()->attach($country);
            }
        }

        $item->similarProducts()->detach();
        if ($item->id) {
            \Illuminate\Support\Facades\DB::table('similar_products')
                ->where('similar_id', $item->id)
                ->orWhere('product_id', $item->id)
                ->delete();
        }
        if ($request->similar) {
            $similar = Product::whereIn('id', $request->similar)->get();
            foreach ($similar as $product) {
                $item->similarProducts()->attach($product);
                $product->similarProducts()->attach($item);
            }
        }

        $item->websites()->detach();
        if ($request->websites) {
            $websites = Website::whereIn('id', $request->websites)->get();
            foreach ($websites as $website) {
                $item->websites()->attach($website);
            }
        }

        //attributes
        $item->attributes()->detach();
        if (is_array($request->product_attributes)) {
            foreach ($request->product_attributes as $id => $value) {
                $attribute = Attribute::find($id);
                if (!$attribute) {
                    continue;
                }
                if ($attribute->type == 0) {
                    if (!is_numeric($value)) {
                        return back()->withErrors([
                            'attribute' => 'Attribute '.$attribute->name.' must have numeric value.'
                        ])->withInput();
                    }
                    $data = ['value_numeric' => $value];
                    $item->attributes()->attach([$id => $data]);
                } elseif ($attribute->type == 1) {
                    if (!is_string($value)) {
                        return back()->withErrors([
                            'attribute' => 'Attribute '.$attribute->name.' must be a string.'
                        ])->withInput();
                    }
                    $data = ['value_text' => $value];
                    $item->attributes()->attach([$id => $data]);
                } elseif ($attribute->type == 2) {
                    $data = ['value_boolean' => !!$value];
                    $item->attributes()->attach([$id => $data]);
                } elseif ($attribute->type == 3) {
                    if (!(bool)strtotime($value)) {
                        return back()->withErrors([
                            'attribute' => 'Attribute '.$attribute->name.' must be a valid date.'
                        ])->withInput();
                    }
                    $data = ['value_date' => $value];
                    $item->attributes()->attach([$id => $data]);
                } elseif ($attribute->type == 4) {
                    $option = $attribute->options()->where('id', $value)->first();
                    if (!$option) {
                        return back()->withErrors([
                            'attribute' => 'Selected option does not exist for attribute '.$attribute->name
                        ])->withInput();
                    }
                    $data = ['attribute_option_id' => $option->id];
                    $item->attributes()->attach([$id => $data]);
                } elseif ($attribute->type == 5) {
                    if (!is_array($value)) {
                        return back()->withErrors([
                            'attribute' => 'Attribute '.$attribute->name.' must have an array of values.'
                        ])->withInput();
                    }
                    $options = $attribute->options()->whereIn('id', $value)->get();
                    foreach ($options as $option) {
                        $item->attributes()->attach([$id => ['attribute_option_id' => $option->id]]);
                    }
                } else {
                    continue;
                }
            }
        }

        //retailer links
        $item->links()->delete();
        if (is_array($request->links)) {
            foreach ($request->links as $key => $linkArr) {
                $link = (object)$linkArr;
                if (Currency::find($link->currency) == null || Agent::find($link->agent) == null) {
                    continue;
                }
                $item->links()->save(ProductLink::create([
                    'agent_id' => $link->agent,
                    'currency_id' => $link->currency,
                    'is_primary' => $request->primary_link == $key,
                    'price_old' => $link->price_old,
                    'price_new' => $link->price_new,
                    'link' => $link->link,
                    'description' => $link->description
                ]));
            }
        }

        $item->save();
        $item->refresh();

        //price_change
        if (($oldPrice !== null && $oldPrice != $item->price_current) ||
            ($oldPriceCurrency !== null && $oldPriceCurrency->id != $item->currentCurrency->id)) {
            $item->priceChanges()->save(ProductPriceChange::create([
                'product_id' => $item->id,
                'price_type' => 'current',
                'price_old' => $oldPrice,
                'currency_old_id' => $oldPriceCurrency->id,
                'price_new' => $item->price_current,
                'currency_new_id' => $item->currentCurrency->id,
                'reason' => 'changed from form'
            ]));
        }

        if (($oldMsrp !== null && $oldMsrp != $item->price_msrp) ||
            ($oldMsrpCurrency !== null && $oldMsrpCurrency != $item->msrpCurrency)) {
            $item->priceChanges()->save(ProductPriceChange::create([
                'product_id' => $item->id,
                'price_type' => 'msrp',
                'price_old' => $oldMsrp,
                'currency_old_id' => $oldMsrpCurrency->id,
                'price_new' => $item->price_msrp,
                'currency_new_id' => $item->msrpCurrency->id,
                'reason' => 'changed from form'
            ]));
        }

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            Product::whereIn('id', $request->items)->delete();
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
