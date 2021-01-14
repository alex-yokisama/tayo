<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Film as Requests;
use Illuminate\Http\RedirectResponse;
use App\Models\Film;
use App\Models\FilmGenre;
use App\Models\Agent;
use App\Models\Website;
use App\Models\AgeRating;

class FilmController extends BaseItemController
{
    protected $baseUrl = '/admin/films';

    public function list(Requests\ListRequest $request)
    {
        $items = Film::orderBy($request->sort, $request->order)->with([
            'genres',
            'director',
            'writer',
            'producer',
            'production_company'
        ]);

        // if ($request->name) {
        //     $items->where('product.name', 'LIKE', '%'.$request->name.'%')
        //         ->orWhere('sku', 'LIKE', '%'.$request->name.'%')
        //         ->orWhere('model', 'LIKE', '%'.$request->name.'%')
        //         ->orWhere('model_family', 'LIKE', '%'.$request->name.'%');
        // }
        //
        // if ($request->text) {
        //     $items->where('excerpt', 'LIKE', '%'.$request->text.'%')
        //         ->orWhere('summary_main', 'LIKE', '%'.$request->text.'%')
        //         ->orWhere('summary_value', 'LIKE', '%'.$request->text.'%')
        //         ->orWhere('full_overview', 'LIKE', '%'.$request->text.'%')
        //         ->orWhere('seo_keywords', 'LIKE', '%'.$request->text.'%')
        //         ->orWhere('tags', 'LIKE', '%'.$request->text.'%');
        // }
        //
        // if ($request->price_from) {
        //     $items->where('price_current', '>=', $request->price_from)
        //         ->orWhere('price_msrp', '>=', $request->price_from);
        // }
        //
        // if ($request->price_to) {
        //     $items->where('price_current', '<=', $request->price_to)
        //         ->orWhere('price_msrp', '<=', $request->price_to);
        // }
        //
        // if ($request->created_at_from) {
        //     $items->where('product.created_at', '>=', $request->created_at_from);
        // }
        //
        // if ($request->date_publish_from) {
        //     $items->where('date_publish', '>=', $request->date_publish_from);
        // }
        //
        // if ($request->created_at_to) {
        //     $items->where('product.created_at', '<=', $request->created_at_to);
        // }
        //
        // if ($request->date_publish_to) {
        //     $items->where('date_publish', '<=', $request->date_publish_to);
        // }
        //
        // if ($request->countries && count($request->countries) > 0) {
        //     $items->whereHas('country', function($q) use ($request) {
        //         $q->whereKey($request->countries);
        //     });
        // }
        //
        // if ($request->brands && count($request->brands) > 0) {
        //     $items->whereHas('brand', function($q) use ($request) {
        //         $q->whereKey($request->brands);
        //     });
        // }
        //
        // if ($request->categories && count($request->categories) > 0) {
        //     $items->whereHas('category', function($q) use ($request) {
        //         $q->whereKey($request->categories);
        //     });
        // }

        $listData = $this->getListData($request);
        $listData['items'] = $items->paginate($request->perPage);

        return view('film.list', $listData);
    }

    public function form(Requests\GetFormRequest $request)
    {
        $formData = $this->getFormData($request);
        $formData['item'] = Film::find($request->id);

        $formData['types'] = Film::types();
        $formData['ageRatings'] = AgeRating::all();

        return view('film.form', $formData);
    }

    public function save(Requests\SaveRequest $request)
    {
        $item = Film::firstOrNew(['id' => $request->id]);

        //general
        $item->name = $request->name;
        $item->type_id = (int)$request->type;
        $item->release_date = $request->release_date;
        $item->trailer_link = $request->trailer_link;
        $item->description = $request->description;
        $item->image = $request->image;

        $age_rating = AgeRating::find($request->age_rating);
        if ($age_rating !== null) {
            $item->age_rating()->associate($age_rating);
        } else {
            return back()->withErrors([
                'age_rating' => 'Selected age rating does not exist'
            ])->withInput();
        }

        $item->genres()->detach();
        if ($request->genres) {
            $genres = FilmGenre::whereKey($request->genres)->get();
            foreach ($genres as $genre) {
                $item->genres()->attach($genre);
            }
        }

        $item->websites()->detach();
        if ($request->websites) {
            $websites = Website::whereKey($request->websites)->get();
            foreach ($websites as $website) {
                $item->websites()->attach($website);
            }
        }

        $item->recomendations()->detach();
        if ($request->recomedations) {
            $films = Film::whereKey($request->recomedations)->get();
            foreach ($films as $film) {
                $item->recomedations()->attach($film);
            }
        }

        $item->actors()->detach();
        if ($request->actors) {
            $agents = Agent::whereKey($request->actors)->get();
            foreach ($agents as $agent) {
                $item->actors()->attach($agent);
            }
        }

        $item->director()->associate(Agent::find($request->director));
        $item->writer()->associate(Agent::find($request->writer));
        $item->producer()->associate(Agent::find($request->producer));
        $item->production_company()->associate(Agent::find($request->production_company));

        $item->save();

        return redirect($request->backUrl)->with([
            'status' => 'success',
            'message' => 'saved successfully'
        ]);
    }

    public function delete(Requests\DeleteRequest $request)
    {
        try {
            Film::whereKey($request->items)->delete();
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
