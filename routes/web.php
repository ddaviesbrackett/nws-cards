<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CAFTGenerationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripeWebHookController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//main
Route::get('/', [HomeController::class, 'home']);

//ultra-special login-and-resume from an email link
Route::get('/email-resume', [OrderController::class, 'emailResume']);

//account creation/view/update: new
Route::controller(OrderController::class)->group(function(){
    Route::get('/new', 'getNew');
    Route::post('/new','postNew');
});

//contact form
Route::post('/contact', [HomeController::class, 'contact']);

//stripe webhook
Route::post('/stripe/webhook', [StripeWebHookController::class, 'handle']);

//profit tracking
Route::controller(TrackingController::class)->group(function(){
    Route::get('/tracking/leaderboard', 'leaderboard')->name('leaderboard');
    Route::get('/tracking/','leaderboard')->name('leaderboard');
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
        Route::post('/account', 'postAccount');

        Route::get('/edit', 'edit');
        Route::post('/edit', 'postEdit');
        Route::get('/Suspend', 'suspend');
        Route::get('/Resume', 'resume');
        Route::get('/account/onetime', 'onetime')->name('account-getonetime');
        Route::post('/account/onetime', 'postOnetime');
    });

    //Administrator treehouse
    Route::middleware('admin')
        ->prefix(('/admin'))
        ->name('admin-')
        ->group(function(){
            Route::controller(AdminController::class)->group(function(){
                Route::model('dateforprofit', 'CutoffDate');
                Route::model('expense', 'Expense');
                Route::model('sale', 'Pointsale');

                Route::get('/caft/{id}', 'caft');
                Route::get('/orders', 'orders');
                Route::get('/order/{id}', 'order');

                Route::get('/impersonate', 'impersonate');
                Route::get('/impersonate/{id}', 'doImpersonate');
                Route::get('/impersonate/unimpersonate', 'unImpersonate');

                Route::get('/orderprofits/{dateforprofit}', 'profitSettingForm')->name('getprofit');
                Route::post('/orderprofits/{dateforprofit}', 'postProfitSettingForm')->name('postprofit');

                Route::get('/pointsale', 'newSale')->name('getnewsale');
                Route::post('/pointsale', 'postNewSale')->name('postnewsale');
                Route::get('/pointsale/{sale}/delete', 'deletePointSale')->name('getdeletesale');
                Route::post('/pointsale/{sale}/delete', 'postDeletePointSale')->name('postdeletesale');
            });

            Route::controller(ExpenseController::class)
                ->prefix('/expenses')
                ->group(function(){
                    Route::get('/', 'expenses')->name('getexpenses');
                    Route::post('/', 'postExpenses')->name('postExpense');

                    Route::get('{expense}/edit', 'editExpense')->name('geteditexpense');
                    Route::post('{expense}/edit', 'postEditExpense')->name('posteditexpense');
                    
                    Route::get('{expense}/delete', 'deleteExpense')->name('getdeleteexpense');
                    Route::post('{expense}/delete', 'postDeleteExpense')->name('postdeleteexpense');
                });

            Route::get('/caftfile/{dateforprofit}', [CAFTGenerationController::class, 'result'])->name('getcaftfile');

        });
});
