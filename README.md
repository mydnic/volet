# An extensible customer feedback widget for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mydnic/volet.svg?style=flat-square)](https://packagist.org/packages/mydnic/volet)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/mydnic/volet/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/mydnic/volet/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/mydnic/volet/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/mydnic/volet/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/mydnic/volet.svg?style=flat-square)](https://packagist.org/packages/mydnic/volet)

Volet is a highly customizable customer interaction widget for Laravel applications that provides a flexible feature system. It comes with one built-in feature: feedback messages collection. But it allows you to create your own custom features.

- 🎨 Fully customizable theme using CSS variables (or by using your own css)
- 🧩 Extensible feature system
- 📝 Built-in feedback message collection
- 🎯 Simple integration with Laravel
- 🛠️ Built with VueJS
- 🔧 Easy to create custom features, or install community made features

Table of contents
=================

<!--ts-->
* [Introduction](#introduction)
* [Installation](#installation)
* [Quickstart](#quickstart)
    * [Style customization](#style-customization)
* [Creating Custom Features](#creating-custom-features)
* [Built-in Features](#built-in-features)
    * [Feedback Messages](#feedback-messages)
<!--te-->

## Introduction

Volet is an open-source widget-like component that you drop on your website to interact with your website's visitors. It's like Crisp, Zendesk, Intercom, Tawkto, etc.

First this package was named Laravel Kustomer. But due to a stupid copyright infringement, I had to rename this package to 'laravel-feedback-component'.

After several years I finally decided to take the time to rebuild it from scratch. It's now called Volet, which means "a panel that can be opened or closed" in French.

At it's core, it's simply a panel that opens up when you click the floating button. Inside that panel, you will decide what options you want to give your users. It can be a simple form, or a chatbot, or anything you want.

By default, Volet comes with one built-in feature: feedback messages collection, which is a simple way for your users to send you a single message.

What's great about Volet is that it's **extensible**. You can create custom features, or install community made features. If you want to make your own chatbot, you can integrate it to Volet! Or if someone else made one, you can install it and use it.

Volet is build using VueJS, but is meant to render any **Web Component**. So you can build your own Web Component (super easy with vuejs, btw), and implement them in Volet. Examples below.

This package does not come with any chat out of the box (yet ?).

## Demo

![Demo of Volet Panel](demo.gif)

## Installation

You can install the package via composer:

```bash
composer require mydnic/volet
```

Publish the assets with:

```bash
php artisan vendor:publish --tag="volet-assets" --force
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="volet-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="volet-config"
```

Have a quick look at `config/volet.php` and update anything you want.

### Upgrade

If you're upgrading from an older version, you should run:

```bash
php artisan vendor:publish --tag="volet-config" --force
php artisan vendor:publish --tag="volet-assets" --force
```

Optionally, you can add this to your `composer.json` to automatically update the assets when you update the package:

```json
{
    "scripts": {
        "post-package-update": [
            "@php artisan vendor:publish --tag=volet-assets --force"
        ]
    }
}
```

## Quickstart

First, create a service provider to configure your Volet features. You can publish our pre-configured provider:

```bash
php artisan vendor:publish --tag="volet-provider"
```

This will create `app/Providers/VoletServiceProvider.php` with some example features already configured.

Register your new service provider in `bootstrap/providers.php` (if you're using Laravel 12 or above):

```php
return [
    // ...
    App\Providers\VoletServiceProvider::class,
];
```

In your `VoletServiceProvider`, register and configure your features:

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Mydnic\Volet\Features\FeedbackMessages;
use Mydnic\Volet\Features\FeatureManager;

class VoletServiceProvider extends ServiceProvider
{
    public function boot(FeatureManager $volet): void
    {
        // Register and configure the Feedback Messages feature
        $this->registerFeedbackMessagesFeature($volet);

        // Example of registering a custom feature
        // $volet->register(new YourCustomFeature());
    }

    private function registerFeedbackMessagesFeature(FeatureManager $volet): void
    {
        $volet->register(
            (new FeedbackMessages)
                // Configure feature display
                ->setLabel('Send us feedback')
                ->setIcon('https://api.iconify.design/lucide:message-square.svg?color=%23888888')

                // Add feedback categories
                ->addCategory(
                    slug: 'general',
                    name: 'General Feedback',
                    icon: 'https://api.iconify.design/lucide:smile.svg?color=%23888888'
                )
                ->addCategory(
                    slug: 'improvement',
                    name: 'Improvement',
                    icon: 'https://api.iconify.design/lucide:lightbulb.svg?color=%23888888'
                )
                ->addCategory(
                    slug: 'bug',
                    name: 'Bug Report',
                    icon: 'https://api.iconify.design/lucide:bug.svg?color=%23888888'
                )
        );
    }
}
```

What's great with this configuration approach is that you can easily add or remove features, based on your needs, for example enable or disable a feature for a specific type of users.

Then add the Volet component to your blade view:

In the `<head>` section:
```blade
    @voletStyles <!-- skip this if you are using your own CSS theme -->
</head>
```

Right before the closing body tag:
```blade
    @volet
</body>
```

If you are planning to use your own CSS theme, you can skip adding the `@voletStyles` directive and add your own CSS file to your `<head>` section.

### Style customization

Volet's default style uses CSS variables for styling. So you can already set your own variables to customize the look and feel of your Volet app.

Add this **after** the `@voletStyles` directive:

```blade
    @voletStyles
    <style>
        :root {
            --volet-background: #FF2D20;
        }
    </style>
</head>
```

All variables are listed here : https://github.com/mydnic/volet/blob/2.x/resources/css/volet.css#L4

## Creating Custom Features

You can create your own features by extending the `BaseFeature` class:

```php
namespace App\Volet\Features;

use Mydnic\Volet\Features\BaseFeature;

class CustomFeature extends BaseFeature
{
    public function getId(): string
    {
        return 'custom-chatbot';
    }
    
    public function getLabel(): string
    {
        return 'Talk with our chatbot';
    }
    
    public function getIcon(): string
    {
        return 'https://api.iconify.design/lucide:star.svg?color=%23888888';
    }
    
    public function getComponentName(): ?string
    {
        return 'custom-feature'; // Name of your Web Component
    }
    
    public function getScripts(): ?string
    {
        $scriptUrl = asset('volet-custom-feature.js');
        return "<script src=\"{$scriptUrl}\"></script>";
    }
    
    public function getConfig(): array
    {
        return [
            'routes' => [
                'store' => route('custom-feature.store'),
            ],
            'labels' => [
                'placeholder' => 'Enter your message...',
                'button' => 'Submit',
                'success' => 'Thank you!',
            ],
            // Add any other configuration your component needs
        ];
    }
}
```

Create a Web Component for your feature's UI, then compile it to a ready to use JS file.

Here's a simple example made with VueJS:
```html
<!-- resources/js/components/CustomFeatureComponent.ce.vue -->
<template>
    <div class="volet-custom-feature">
        <button class="volet-custom-button">
            Click me
        </button>
    </div>
</template>

<script setup>
defineProps({
    config: {
        type: Object,
        required: true,
    },
})
</script>

<style>
/**
 * Add your custom CSS here
 */
</style>
```

```js
// resources/js/volet-custom-feature.js
import { defineCustomElement } from 'vue'
import CustomFeatureComponent from './components/CustomFeatureComponent.ce.vue'

const Element = defineCustomElement(CustomFeatureComponent)

customElements.define('custom-feature', Element)
```

```js
// vite.config.js
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        vue({
            template: {
                compilerOptions: {
                    isCustomElement: (tag) => tag.includes('custom-feature'),
                }
            }
        })
    ],
    define: {
        'process.env.NODE_ENV': JSON.stringify('production'),
    },
    build: {
        lib: {
            entry: resolve(__dirname, 'resources/js/volet-custom-feature.js'),
            name: 'CustomFeature',
            fileName: () => `volet-custom-feature.js`,
            formats: ['iife'],
        },
        outDir: 'public/',
    }
});
```

As we are working with Web Components, you can use any framework to build your component, with any CSS framework.

That's it ! Volet will automatically load your feature and display it in the panel, as long as the feature is registered and enabled.

## Built-in Features

### Feedback Messages

The feedback messages feature allows users to submit feedback in different categories. Configure the table name in your `config/volet.php`:

```php
return [
    'feedback-messages' => [
        'table' => 'custom_feedback_table', // Default: 'volet_feedback_messages'
        
        // ...
    ],
];
```

Configure the feature in your `VoletServiceProvider`:

```php
use Mydnic\Volet\Features\FeedbackMessages;

$volet->register(
    (new FeedbackMessages())
        // Configure feature display
        ->setLabel('Send us feedback')
        ->setIcon('https://api.iconify.design/lucide:message-square.svg?color=%23888888')
        
        // Add feedback categories
        ->addCategory(
            slug: 'bug',
            name: 'Bug Report',
            icon: 'https://api.iconify.design/lucide:bug.svg?color=%23888888'
        )
        ->addCategory(
            slug: 'improvement',
            name: 'Improvement',
            icon: 'https://api.iconify.design/lucide:lightbulb.svg?color=%23888888'
        )
);
```

You can enable/disable the feature:
```php
(new FeedbackMessages())->disable(); // or ->enable()
```

Or use conditional configuration:
```php
(new FeedbackMessages())
    ->when(app()->isProduction(), fn ($f) => $f
        ->addCategory('bug', 'Production Bug Report', 'icon-url')
    );
```

#### Notifications

You can easily use Laravel model's events to send notifications when a new feedback message is submitted:

```php
namespace App\Observers;

use Mydnic\Volet\Models\FeedbackMessage;
use App\Notifications\NewFeedbackMessageNotification;
use Illuminate\Support\Facades\Notification;

class FeedbackMessageObserver
{
    public function created(FeedbackMessage $feedbackMessage)
    {
        // Send notification to administrators
        Notification::route('mail', 'admin@example.com')
            ->notify(new NewFeedbackMessageNotification($feedbackMessage));
    }
}
```

```php
// Register the observer in your AppServiceProvider
public function boot()
{
    FeedbackMessage::observe(FeedbackMessageObserver::class);
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Clément Rigo](https://github.com/mydnic)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
