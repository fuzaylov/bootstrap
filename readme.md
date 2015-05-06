# Laravel Bootstrap Helpers

If you find yourself entering the same HTML and blade code just to display an html form field, this package might be for you.

## Install

Pull this package in through Composer.

```js
{
    "require": {
        "fuzaylov/bootstrap": "0.2.8"
    }
}
```

### Laravel 5

Note, for laravel 4, use fuzaylov/bootstrap version 0.2.5

Once installed, you need to register Laravel service provider, in your `app/config/app.php`:

```php
'providers' => array(
	...
	'Illuminate\Html\HtmlServiceProvider',
    'Fuzaylov\Bootstrap\BootstrapServiceProvider'
)

'aliases' => array(
    ...
    'Form'      => 'Illuminate\Html\FormFacade',
)
```

## Usage

See tests/sample.blade.php for some sample usages. For a full list of supported fields, see Bootstrap.php

Have fun!

Aleks