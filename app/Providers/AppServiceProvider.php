<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
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
        Model::automaticallyEagerLoadRelationships();

        FileUpload::configureUsing(fn(FileUpload $fileUpload) => $fileUpload
            ->visibility('public'));

        ImageColumn::configureUsing(fn(ImageColumn $imageColumn) => $imageColumn
            ->visibility('public'));

        ImageEntry::configureUsing(fn(ImageEntry $imageEntry) => $imageEntry
            ->visibility('public'));
    }
}
