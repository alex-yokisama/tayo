<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers as Controllers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

Route::prefix('admin')->middleware(['auth', 'verified', 'can:use admin panel'])->group(function () {
    Route::get('/', function () {
        return redirect('admin/products');
    });

    $entities = collect([
        ['name' => 'role', 'plural' => 'roles', 'controller' => Controllers\RoleController::class],
        ['name' => 'user', 'plural' => 'users', 'controller' => Controllers\UserController::class],
        ['name' => 'country', 'plural' => 'countries', 'controller' => Controllers\CountryController::class],
        ['name' => 'brand', 'plural' => 'brands', 'controller' => Controllers\BrandController::class],
        ['name' => 'measure', 'plural' => 'measures', 'controller' => Controllers\MeasureController::class],
        ['name' => 'agent', 'plural' => 'agents', 'controller' => Controllers\AgentController::class],
        ['name' => 'currency', 'plural' => 'currencies', 'controller' => Controllers\CurrencyController::class],
        ['name' => 'attribute_group', 'plural' => 'attribute_groups', 'controller' => Controllers\AttributeGroupController::class],
        ['name' => 'attribute', 'plural' => 'attributes', 'controller' => Controllers\AttributeController::class],
        ['name' => 'category', 'plural' => 'categories', 'controller' => Controllers\CategoryController::class],
        ['name' => 'product', 'plural' => 'products', 'controller' => Controllers\ProductController::class],
        ['name' => 'website', 'plural' => 'websites', 'controller' => Controllers\WebsiteController::class],
        ['name' => 'license', 'plural' => 'licenses', 'controller' => Controllers\LicenseController::class],
        ['name' => 'os', 'plural' => 'oss', 'controller' => Controllers\OSController::class],
        ['name' => 'film_genre', 'plural' => 'film_genres', 'controller' => Controllers\FilmGenreController::class],
        ['name' => 'age_rating', 'plural' => 'age_ratings', 'controller' => Controllers\AgeRatingController::class],
        ['name' => 'film', 'plural' => 'films', 'controller' => Controllers\FilmController::class],
        ['name' => 'film_review', 'plural' => 'film_reviews', 'controller' => Controllers\FilmReviewController::class],
        ['name' => 'app', 'plural' => 'apps', 'controller' => Controllers\AppController::class],
    ])->map(function ($item) {
        return (object)$item;
    });
    foreach ($entities as $entity) {
        Route::middleware(['can:view '.$entity->plural])->group(function() use ($entity) {
            Route::get($entity->plural, [$entity->controller, 'list']);
            Route::middleware(['can:update '.$entity->plural])->group(function() use ($entity) {
                Route::get($entity->name, [$entity->controller, 'form']);
                Route::post($entity->name, [$entity->controller, 'save']);
            });
            Route::middleware(['can:delete '.$entity->plural])->post('delete_'.$entity->plural, [$entity->controller, 'delete']);
        });
    }
});
