<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Category;
use App\Models\Plate;
use App\Models\Ingredient;
use App\Policies\CategoryPolicy;
use App\Policies\PlatePolicy;
use App\Policies\IngredientPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::define('admin', fn(User $user) => $user->isAdmin());
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Plate::class, PlatePolicy::class);
        Gate::policy(Ingredient::class, IngredientPolicy::class);
    }
}
