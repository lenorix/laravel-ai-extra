<?php

namespace Lenorix\LaravelAiExtra;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Lenorix\LaravelAiExtra\Commands\LaravelAiExtraCommand;

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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_ai_extra_table')
            ->hasCommand(LaravelAiExtraCommand::class);
    }
}
