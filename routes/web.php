<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard;
use App\Livewire\Deposits\Index as DepositsIndex;
use App\Livewire\Expenses\Index as ExpensesIndex;
use App\Livewire\Home;
use App\Livewire\Meals\Index as MealsIndex;
use App\Livewire\Members\Index as MembersIndex;
use App\Livewire\Manager\Transfer as ManagerTransfer;
use App\Livewire\Profile\Index as ProfileIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/members', MembersIndex::class)->name('members.index');
    Route::get('/deposits', DepositsIndex::class)->name('deposits.index');
    Route::get('/expenses', ExpensesIndex::class)->name('expenses.index');
    Route::get('/meals', MealsIndex::class)->name('meals.index');
    Route::get('/profile', ProfileIndex::class)->name('profile');
    Route::get('/manager/transfer', ManagerTransfer::class)->name('manager.transfer');
});
