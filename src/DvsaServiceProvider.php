<?php

namespace FourthDimension\Dvsa;

use FourthDimension\Dvsa\Commands\DvsaCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DvsaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-dvsa')
            ->hasConfigFile();
        // ->hasCommand(DvsaCommand::class);
    }
}
