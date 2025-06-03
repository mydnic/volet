<?php

namespace Mydnic\Volet\Tests;

use Mydnic\Volet\Features\FeatureManager;
use Mydnic\Volet\Features\FeedbackMessages;
use Mydnic\Volet\VoletServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Configure the package
        $this->app['config']->set('volet.feedback-messages.table', 'feedback_messages');
        $this->app['config']->set('volet.feedback-messages.model', \Mydnic\Volet\Models\FeedbackMessage::class);
        $this->app['config']->set('volet.feedback-messages.controller', \Mydnic\Volet\Http\Controllers\FeedbackMessageController::class);
        $this->app['config']->set('volet.feedback-messages.routes', [
            'prefix' => 'feedback',
            'middleware' => ['web'],
        ]);

        // Register feedback feature
        $feature = new FeedbackMessages;
        $feature->addCategory('bug', 'Bug Report', 'bug-icon.svg')
            ->enable();
        $this->app->make(FeatureManager::class)->register($feature);

        // Run migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            VoletServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Set the application key for encryption
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }
}
