<?php

namespace RuangDeveloper\LivewireTable;

use Illuminate\Support\ServiceProvider;

class LivewireTableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'livewire-table');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/livewire-table'),
        ], 'views');
    }
}
