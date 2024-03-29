<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CAFTGenerationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

//main
Route::get('/', [HomeController::class, 'home'])->name('home');

//ultra-special login-and-resume from an email link
Route::get('/email-resume', [OrderController::class, 'emailResume']);

//account creation/view/update: new
Route::controller(OrderController::class)->group(function(){
    Route::get('/new', 'getNew');
    Route::post('/new','postNew');
});

//contact form
Route::post('/contact', [HomeController::class, 'contact']);

//profit tracking
Route::controller(TrackingController::class)->group(function(){
    Route::get('/tracking','toLeaderboard')->name('to-leaderboard');
    Route::get('/tracking/leaderboard', 'leaderboard')->name('leaderboard');
    Route::get('tracking/{bucketname}', 'bucket')->name('tracking-bucket');
});

//authenticated routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::controller(OrderController::class)->group(function(){
        Route::get('/account', 'account')->name('account');
        Route::get('/account/extracards', 'account'); //with onetime form showing

        Route::get('/edit', 'edit');
        Route::post('/edit', 'postEdit');
        Route::get('/Suspend', 'suspend');
        Route::get('/Resume', 'resume');
        Route::get('/account/onetime', 'onetime')->name('account-getonetime');
        Route::post('/account/onetime', 'postOnetime');
    });

    //impersonation (restricted to admins by rules on User)
    Route::impersonate();

    //Administrator treehouse
    Route::middleware('admin')
        ->prefix(('/admin'))
        ->name('admin-')
        ->group(function(){
            Route::controller(AdminController::class)->group(function(){

                Route::get('/caft/{id}', 'caft')->name('caft');
                Route::get('/orders', 'orders')->name('orders');
                //order profit add/edit done with a livewire component
                Route::get('/order/{id}', 'order')->name('order');

                Route::get('/impersonate', 'impersonate')->name('impersonateList');

                /*
                //a vestigial feature. Allowed for tracking of speculatively-bought cards at the school gate; no longer done.
                Route::get('/pointsale', 'newSale')->name('newsale');
                Route::post('/pointsale', 'postNewSale')->name('postnewsale');
                Route::get('/pointsale/{sale}/delete', 'deletePointSale')->name('deletesale');
                Route::post('/pointsale/{sale}/delete', 'postDeletePointSale')->name('postdeletesale');
                */
            });

            Route::controller(ExpenseController::class)
                ->prefix('/expenses')
                ->group(function(){
                    Route::get('/', 'expenses')->name('expenses');
                    //expense add/edit done with a livewire component
                    
                    Route::delete('{id}/delete', 'deleteExpense')->name('deleteexpense');
                });

            Route::get('/caftfile/{cutoffId}', [CAFTGenerationController::class, 'result'])->name('getcaftfile');

        });
});
