<?php

use App\Http\Controllers\Api\V1\ThreadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\Relationships;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

JsonApiRoute::server('v1')
    ->prefix('v1')
    ->resources(function (ResourceRegistrar $server) {
        $server->resource('users', JsonApiController::class)->readOnly();
        $server->resource('threads', ThreadController::class)
            ->relationships(function (Relationships $relations) {
                $relations->hasOne('user')->readOnly();
            })
            ->actions('-actions', function ($actions) {
                $actions->withId()->post('lock', 'switchLock');
            });
    });
