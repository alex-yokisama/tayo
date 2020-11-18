<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function list()
    {
        return view('country.list', ['countries' => Country::all()]);
    }
}
