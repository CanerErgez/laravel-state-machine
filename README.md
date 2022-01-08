

# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/caner/state-machine.svg?style=flat-square)](https://packagist.org/packages/caner/state-machine)    
[![Total Downloads](https://img.shields.io/packagist/dt/caner/state-machine.svg?style=flat-square)](https://packagist.org/packages/caner/state-machine)    
[![run-tests](https://github.com/CanerErgez/laravel-state-machine/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/CanerErgez/laravel-state-machine/actions/workflows/main.yml)

This package are helps you about to create State Machine data model based services.

## Installation

You can install the package via composer:

```bash 
composer require caner/state-machine  
```   
## Usage

In `config/app.php`;
```php 
\Caner\StateMachine\StateMachineServiceProvider::class,  
```  

and publish config file;
```php 
php artisan vendor:publish --tag=state-machine-config  
```  

### Step by step documentation

[1-) Introducing Concept  ](https://github.com/CanerErgez/laravel-state-machine/tree/main/docs/CONCEPT.md)
2-) Create Your First State Machine  
3-) Create Your First State  
4-) Create Your First Transition  
5-) Create Your First Guard  
5-) Create Your First AfterAction
6-) Transition in Created State Machine
7-) Create Another State Machine

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please create an issue on github issues section.

## Credits
Special thank you for [Tarfin Labs](https://github.com/tarfin-labs)

- [Caner Ergez](https://github.com/CanerErgez)
- Will be update soon.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
