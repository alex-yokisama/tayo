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
        return redirect('admin/roles');
    });

    $entities = collect([
        ['name' => 'role', 'plural' => 'roles', 'controller' => Controllers\RoleController::class, 'viewable' => true, 'updatable' => true, 'deletable' => true],
        ['name' => 'user', 'plural' => 'users', 'controller' => Controllers\UserController::class, 'viewable' => true, 'updatable' => true, 'deletable' => false],
        ['name' => 'country', 'plural' => 'countries', 'controller' => Controllers\CountryController::class, 'viewable' => true, 'updatable' => true, 'deletable' => true],
        ['name' => 'brand', 'plural' => 'brands', 'controller' => Controllers\BrandController::class, 'viewable' => true, 'updatable' => true, 'deletable' => true],
        ['name' => 'measure', 'plural' => 'measures', 'controller' => Controllers\MeasureController::class, 'viewable' => true, 'updatable' => true, 'deletable' => true],
        ['name' => 'agent', 'plural' => 'agents', 'controller' => Controllers\AgentController::class, 'viewable' => true, 'updatable' => true, 'deletable' => true],
        ['name' => 'currency', 'plural' => 'currencies', 'controller' => Controllers\CurrencyController::class, 'viewable' => true, 'updatable' => true, 'deletable' => true],
        ['name' => 'attribute', 'plural' => 'attributes', 'controller' => Controllers\AttributeController::class, 'viewable' => true, 'updatable' => true, 'deletable' => true],
        ['name' => 'category', 'plural' => 'categories', 'controller' => Controllers\CategoryController::class, 'viewable' => true, 'updatable' => true, 'deletable' => true],
    ])->map(function ($item) {
        return (object)$item;
    });
    foreach ($entities as $entity) {
        if ($entity->viewable) {
            Route::middleware(['can:view '.$entity->plural])->group(function() use ($entity) {
                Route::get($entity->plural, [$entity->controller, 'list']);
                if ($entity->updatable) {
                    Route::middleware(['can:update '.$entity->plural])->group(function() use ($entity) {
                        Route::get($entity->name, [$entity->controller, 'form']);
                        Route::post($entity->name, [$entity->controller, 'save']);
                    });
                }
                if ($entity->deletable) {
                    Route::middleware(['can:delete '.$entity->plural])->post('delete_'.$entity->plural, [$entity->controller, 'delete']);
                }
            });
        }
    }
});
