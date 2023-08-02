# laravelminifyobfuscate
[![License](http://poser.pugx.org/ivannofick/laravelminifyobfuscate/license)](https://packagist.org/packages/ivannofick/laravelminifyobfuscate) 
[![Latest Stable Version](http://poser.pugx.org/ivannofick/laravelminifyobfuscate/v)](https://packagist.org/packages/ivannofick/laravelminifyobfuscate) 
[![PHP Version Require](http://poser.pugx.org/ivannofick/laravelminifyobfuscate/require/php)](https://packagist.org/packages/ivannofick/laravelminifyobfuscate)
[![Total Downloads](http://poser.pugx.org/ivannofick/laravelminifyobfuscate/downloads)](https://packagist.org/packages/ivannofick/laravelminifyobfuscate) 
## Obfuscate your Blade templates in Laravel by encrypting HTML, CSS, and JS into one HTML file

Laravel Minify Obfuscate <img align="left" alt="ReactJs" width="100px" src="https://ruangapp.com/assets/img/logos/logo-1.svg" style="padding-right:10px;" />


## Introduction

The "Laravel Minify Obfuscate" package allows you to enhance the security of your Laravel Blade templates by obfuscating and encrypting the HTML, CSS, and JavaScript code into a single HTML file. This makes it harder for potential attackers to access and understand your frontend code. The package achieves this by providing middleware that automatically processes the Blade templates and minifies/obfuscates the output.

This README.md file will guide you through the process of setting up and using the package in your Laravel application.

## Installation

1. Install the package via Composer by running the following command:
```bash
composer require ivannofick/laravelminifyobfuscate
```

2. After the package is installed, publish the package configuration file and assets using the following Artisan command:
```
php artisan vendor:publish --provider="Ivannofick\Laravelminifyobfuscate\MinifyObfuscateProvider"
```
## Middleware Setup
To enable the minification and obfuscation for your Blade templates, you need to add the provided middleware to your application's kernel. Follow these steps:

1. Open the app/Http/Kernel.php file in your Laravel project.
2. Locate the $middleware array and add the following middleware classes:
```
\Ivannofick\Laravelminifyobfuscate\Middleware\MinifyHtml::class,
\Ivannofick\Laravelminifyobfuscate\Middleware\MinifyJavascript::class,
```
The final $middleware array should look something like this:
```
protected $middleware = [
    // Other middleware classes...
    \Ivannofick\Laravelminifyobfuscate\Middleware\MinifyHtml::class,
    \Ivannofick\Laravelminifyobfuscate\Middleware\MinifyJavascript::class,
];
```
3. Save the changes to the Kernel.php file.
### Conditional Obfuscation
The "Laravel Minify Obfuscate" package also provides a feature called "laravel_obfuscate_conditional," which allows you to enable or disable the obfuscation based on a configuration setting. This can be useful when you want to control whether obfuscation should be active in certain environments or scenarios.

1. To use the `laravel_obfuscate_conditional` feature, follow these steps:
2. Open the config/minifyobfuscate.php configuration file.
Find the laravel_obfuscate_conditional setting and set it to either true or false as per your requirement:
```
'laravel_obfuscate_conditional' => true,
```
Setting it to `true` will activate the obfuscation, while setting it to false will deactivate it.
3. Save the changes to the configuration file.

## Usage
With the package installed, middleware set up, and conditional obfuscation configured, your Blade templates' obfuscation will be based on the value of the `LARAVEL_OBFUSCATE_SECURE_KEY` setting. If it is set to true, obfuscation will be active, and if it is set to `false`, obfuscation will be disabled.

Please note that this conditional obfuscation setting adds flexibility, allowing you to enable or disable obfuscation as needed for different environments or scenarios.

Conclusion
Congratulations! You have successfully set up "Laravel Minify Obfuscate" to protect your Blade templates in Laravel. Your frontend code is now minified and obfuscated, improving the security of your application.

If you encounter any issues or want to contribute to the package, please check out the GitHub repository.

Happy coding!

