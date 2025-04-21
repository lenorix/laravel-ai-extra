<?php

namespace Lenorix\LaravelAiExtra;

use Lenorix\LaravelAiExtra\Commands\LaravelAiExtraCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAiExtraServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-ai-extra')
            /*->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_ai_extra_table')
            ->hasCommand(LaravelAiExtraCommand::class)*/;
    }
}
