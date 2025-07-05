<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CAFTGenerationController;
use App\Http\Controllers\Admin\EmailPreviewController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function(){
    Route::get('/', 'home')->name('home'); //main
    Route::post('/contact', 'contact'); //contact form
});

//ultra-special login-and-resume from an email link - vestigial
Route::get('/email-resume', [OrderController::class, 'emailResume']);

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
        Route::get('/account/extracards', 'account')->name('account-extracards'); //with onetime form showing

        Route::get('/edit', 'edit')->name('edit');
        Route::post('/edit', 'postEdit')->name('postEdit');
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
                //if/when revived, will probably be a livewire component like profit and expenses.
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

            Route::controller(ClassController::class)
                ->prefix('/classes')
                ->group(function(){
                    Route::get('/', 'classes')->name('classes');
                    //class add/edit done with a livewire component
                });
            
            if(App::environment('local'))
            {
            Route::controller(EmailPreviewController::class)
                ->prefix('/emailPreview')
                ->group(function(){
                    Route::get('/chargeReminder/{id?}', 'chargeReminder');
                    Route::get('/contact', 'contact');
                    Route::get('/deadlineReminder/{id?}', 'deadlineReminder');
                    Route::get('/new/{id?}', 'new');
                    Route::get('/edit/{id?}', 'edit');
                    Route::get('/orderbeg/{id?}', 'orderbeg');
                    Route::get('/pickupReminder/{id?}', 'pickupReminder');
                    Route::get('/suspend/{id?}', 'suspend');
                });
            }

            Route::get('/caftfile/{cutoffId}', [CAFTGenerationController::class, 'result'])->name('getcaftfile');

        });
});
