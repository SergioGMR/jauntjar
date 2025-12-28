<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\ImageEntry;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Gate::define('viewPulse', function (User $user) {
           return in_array($user->email, [
                'sergiogmr@icloud.com'
            ]);
        });

        Model::automaticallyEagerLoadRelationships();

        FileUpload::configureUsing(fn(FileUpload $fileUpload) => $fileUpload
            ->visibility('public'));

        ImageColumn::configureUsing(fn(ImageColumn $imageColumn) => $imageColumn
            ->visibility('public'));

        ImageEntry::configureUsing(fn(ImageEntry $imageEntry) => $imageEntry
            ->visibility('public'));
    }
}
