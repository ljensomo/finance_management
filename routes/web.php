<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BudgetDetailController;
use App\Http\Controllers\BudgetTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\IncomeSourceController;
use App\Http\Controllers\IncomeTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

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

// login routes
Route::get('login', function () { return view('login'); })->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/logout', [LoginController::class, 'logout']);

Route::group(['middleware' => 'auth'], function(){
    // index
    Route::get('/', function(){ return view('index'); });

    // dashboard
    Route::get('/dashboard/expense-summary', [DashboardController::class, 'getExpenseSummary']);

    // incomes
    Route::resource('incomes', IncomeController::class, ['except' => ['create', 'edit', 'show']]);
    Route::post('incomes/update', [IncomeController::class, 'update']);
    Route::get('incomes/list', [IncomeController::class, 'list']);
    Route::get('incomes/{id}/get', [IncomeController::class, 'get']);

    // Income Sources
    Route::resource('income-sources', IncomeSourceController::class, ['except' => ['create', 'edit', 'show']]);
    Route::post('income-sources/update', [IncomeSourceController::class, 'update']);
    Route::get('income-sources/list', [IncomeSourceController::class, 'list']);
    Route::get('income-sources/{id}/get', [IncomeSourceController::class, 'get']);
    Route::get('income-sources/json-list', [IncomeSourceController::class, 'jsonList']);
    
    // Expenses
    Route::resource('expenses', ExpenseController::class, ['except' => ['create', 'edit', 'show']]);
    Route::post('expenses/update', [ExpenseController::class, 'update']);
    Route::get('expenses/list', [ExpenseController::class, 'list']);
    Route::get('expenses/{id}/get', [ExpenseController::class, 'get']);
    
    // User Controller
    Route::resource('users', UserController::class, ['except' => ['create', 'show']]);
    Route::get('/register-user', function (){ return view('register-user'); });
    Route::post('/user/status/change', [UserController::class, 'changeStatus']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::get('/user/list', [UserController::class, 'list']);

    // Income Types
    Route::get('/income-types/list', [IncomeTypeController::class, 'list']);

    // Accounts
    Route::resource('accounts', AccountController::class, ['except' => ['create', 'edit', 'show']]);
    Route::post('accounts/update', [AccountController::class, 'update']);
    Route::post('accounts/transfer', [AccountController::class, 'transferAmount']);
    Route::get('accounts/list', [AccountController::class, 'list']);
    Route::get('accounts/{id}/get', [AccountController::class, 'get']);
    Route::get('accounts/{id}/json-list', [AccountController::class, 'jsonList']);

    // Expense Category
    Route::resource('expense-categories', ExpenseCategoryController::class, ['except' => ['create', 'edit', 'show']]);
    Route::post('expense-categories/update', [ExpenseCategoryController::class, 'update']);
    Route::get('expense-categories/list', [ExpenseCategoryController::class, 'list']);
    Route::get('expense-categories/{id}/get', [ExpenseCategoryController::class, 'get']);
    Route::get('expense-categories/json-list', [ExpenseCategoryController::class, 'jsonList']);

    // Budgets
    Route::resource('budgets', BudgetController::class, ['except' => ['create', 'edit', 'show']]);
    Route::post('budgets/update', [BudgetController::class, 'update']);
    Route::get('budgets/list', [BudgetController::class, 'list']);
    Route::get('budgets/{id}/get', [BudgetController::class, 'get']);

    // Budget Types
    Route::get('budget-types/json-list', [BudgetTypeController::class, 'jsonList']);

    // Budget Details
    Route::resource('budget-details', BudgetDetailController::class, ['except' => ['index', 'create', 'edit', 'show']]);
    Route::post('budget-details/update', [BudgetDetailController::class, 'update']);
    Route::get('budget-details/{id}/list', [BudgetDetailController::class, 'list']);
    Route::get('budget-details/{id}/get', [BudgetDetailController::class, 'get']);
    Route::get('budget-details/{id}/view', [BudgetDetailController::class, 'view']);

});

