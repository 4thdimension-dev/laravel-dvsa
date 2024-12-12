<?php

namespace FourthDimension\Dvsa;

use FourthDimension\Dvsa\Commands\DvsaCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DvsaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-dvsa')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_dvsa_table')
            ->hasCommand(DvsaCommand::class);
    }
}
