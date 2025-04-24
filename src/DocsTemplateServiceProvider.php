<?php

namespace Prezet\DocsTemplate;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DocsTemplateServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $packageName = 'prezet/docs-template'; // Define package name

        $package
            ->name('docs-template')
            ->hasViews()
            ->hasInstallCommand(function (InstallCommand $command) use ($packageName) {
                $command->startWith(function (InstallCommand $command) use ($packageName) {
                    $command->info('Installing Prezet Docs Template...');

                    $this->copyRoutes($command);
                    $this->copyControllers($command);
                    $this->copyViews($command);
                    $this->copyCss($command);
                    $this->copyContent($command);
                    $this->copyViteConfig($command);
                    $this->installNodeDependencies($command);
                    Process::run('php artisan prezet:index --fresh');

                    $this->removeSelf($command, $packageName);

                    $command->newLine();
                    $command->info('Prezet Docs Template installed successfully.');
                });
            });
    }

    protected function copyRoutes(InstallCommand $command): void
    {
        $source = self::packagePath('routes/prezet.php');
        $destination = base_path('routes/prezet.php');

        if (! File::exists($destination)) {
            $command->info('Copying routes file...');
            File::copy($source, $destination);
        } else {
            $command->warn('Skipping copying routes file: already exists.');
        }

        $webRoutePath = base_path('routes/web.php');
        $webRouteContent = File::get($webRoutePath);
        $include = "require __DIR__.'/prezet.php';";

        if (! str_contains($webRouteContent, $include)) {
            $command->info('Adding prezet routes to web.php...');
            File::append($webRoutePath, "\n".$include);
        } else {
            $command->warn('Skipping adding prezet routes to web.php: already included.');
        }
    }

    protected function copyControllers(InstallCommand $command): void
    {
        $sourceDir = self::packagePath('app/Http/Controllers/Prezet');
        $destinationDir = app_path('Http/Controllers/Prezet');

        if (! File::isDirectory($destinationDir)) {
            $command->info('Creating Prezet controller directory...');
            File::makeDirectory($destinationDir, 0755, true);
        }

        $command->info('Copying Prezet controllers...');
        File::copyDirectory($sourceDir, $destinationDir);
    }

    protected function copyViews(InstallCommand $command): void
    {
        // 1. Copy page views (resources/views/prezet)
        $sourceDirPrezet = self::packagePath('resources/views/prezet');
        $destinationDirPrezet = resource_path('views/prezet');

        if (! File::isDirectory($destinationDirPrezet)) {
            $command->info('Creating Prezet views directory: '.$destinationDirPrezet);
            File::makeDirectory($destinationDirPrezet, 0755, true);
        }

        $command->info('Copying Prezet views to '.$destinationDirPrezet);
        File::copyDirectory($sourceDirPrezet, $destinationDirPrezet);

        // 2. Copy component views (resources/views/components/prezet)
        $sourceDirComponents = self::packagePath('resources/views/components/prezet');
        $destinationDirComponents = resource_path('views/components/prezet');

        if (! File::isDirectory($destinationDirComponents)) {
            $command->info('Creating Prezet components directory: '.$destinationDirComponents);
            File::makeDirectory($destinationDirComponents, 0755, true);
        }

        $command->info('Copying Prezet view components to '.$destinationDirComponents);
        File::copyDirectory($sourceDirComponents, $destinationDirComponents);
    }

    protected function copyCss(InstallCommand $command): void
    {
        $command->info('Copying CSS and Tailwind config...');

        $cssSource = self::packagePath('resources/css/prezet.css');
        $cssDestination = resource_path('css/prezet.css');
        if (! File::exists($cssDestination)) {
            File::copy($cssSource, $cssDestination);
        } else {
            $command->warn('Skipping copying prezet.css: already exists.');
        }
    }

    protected function copyContent(InstallCommand $command): void
    {
        $sourceDir = self::packagePath('content');
        $destinationDir = base_path('prezet');

        $command->info('Copying content directory to '.$destinationDir);
        if (File::isDirectory($sourceDir)) {
            File::copyDirectory($sourceDir, $destinationDir);
        } else {
            $command->error('Source content directory not found: '.$sourceDir);
        }
    }

    protected function copyViteConfig(InstallCommand $command): void
    {
        $source = self::packagePath('vite.config.js');
        $destination = base_path('vite.config.js');

        if (! File::exists($source)) {
            $command->error('Source vite.config.js not found: '.$source);

            return;
        }

        $command->info('Copying vite.config.js...');

        $fileExists = File::exists($destination);

        File::copy($source, $destination);

        if ($fileExists) {
            $command->warn('Overwrote existing vite.config.js. Please review the file ('.$destination.') to ensure it meets your project\'s requirements.');
        }
    }

    protected function installNodeDependencies(InstallCommand $command): void
    {
        $command->info('Installing node dependencies...');

        $packages = [
            'alpinejs',
            '@tailwindcss/forms',
            '@tailwindcss/typography',
            '@tailwindcss/vite',
            'tailwindcss@4',
            'vite-plugin-watch-and-run',
        ];

        if (File::exists(base_path('pnpm-lock.yaml'))) {
            $bin = 'pnpm';
            $commandVerb = 'add -D';
        } elseif (File::exists(base_path('yarn.lock'))) {
            $bin = 'yarn';
            $commandVerb = 'add -D';
        } else {
            $bin = 'npm';
            $commandVerb = 'install --save-dev';
        }

        $packageString = implode(' ', $packages);
        $command->comment("Running: {$bin} {$commandVerb} {$packageString}");

        Process::run("{$bin} {$commandVerb} {$packageString}", function (string $type, string $output) use ($command) {
            $command->line($output);
        });

        $command->info('Building assets...');
        $command->comment("Running: {$bin} run build");
        Process::run("{$bin} run build", function (string $type, string $output) use ($command) {
            $command->line($output);
        });
    }

    protected function removeSelf(InstallCommand $command, string $packageName): void
    {
        $command->info("Removing the template installer package ({$packageName})...");

        Process::run("composer remove {$packageName} --dev", function (string $type, string $output) use ($command) {
            $command->line($output);
        });

        $command->comment('Template package removed.');
    }

    /**
     * Get the path to the package root.
     */
    protected static function packagePath(string $path = ''): string
    {
        return __DIR__.'/../'.ltrim($path, '/');
    }
}
