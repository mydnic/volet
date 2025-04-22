<?php

namespace Mydnic\Volet;

use Illuminate\Support\Facades\Blade;
use Mydnic\Volet\Commands\VoletCommand;
use Mydnic\Volet\Features\Feature;
use Mydnic\Volet\Features\FeatureManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class VoletServiceProvider extends PackageServiceProvider
{
    protected FeatureManager $featureManager;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->featureManager = new FeatureManager;
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('volet')
            ->hasConfigFile()
            ->hasRoute('api')
            ->hasMigration('create_feedback_messages_table')
            ->hasCommand(VoletCommand::class)
            ->hasTranslations()
            ->hasAssets();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(FeatureManager::class, function () {
            return $this->featureManager;
        });
    }

    public function boot()
    {
        parent::boot();

        // Register Blade Directives
        Blade::directive('voletStyles', function () {
            $styleUrl = asset('vendor/volet/volet-default.css');

            return "<?php echo '<link rel=\"stylesheet\" href=\"{$styleUrl}\">'; ?>";
        });

        Blade::directive('volet', function () {
            $scriptUrl = asset('vendor/volet/volet-app.js');
            $icon = htmlspecialchars(config('volet.icon'));
            $labels = htmlspecialchars(json_encode(trans('volet::volet')));

            $scripts = $this->app->make(FeatureManager::class)->getEnabledFeatures()
                ->map(function (Feature $feature) {
                    return $feature->getScripts();
                })
                ->values()
                ->filter()
                ->join("\n");

            $output = "<?php echo '<div
                id=\"volet\"
                data-icon=\"{$icon}\"
                data-labels=\"{$labels}\"
                ></div>'; ?>";

            $output .= "<?php echo '<script src=\"{$scriptUrl}\"></script>'; ?>";

            $output .= $scripts;

            return $output;
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/dist' => public_path('vendor/volet'),
            ], 'volet-assets');

            $this->publishes([
                __DIR__.'/../stubs/VoletServiceProvider.stub.php' => app_path('Providers/VoletServiceProvider.php'),
            ], 'volet-provider');
        }
    }
}
