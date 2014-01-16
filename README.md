# Facilius
[![Build Status](https://travis-ci.org/tmont/facilius.png)](https://travis-ci.org/tmont/facilius)

This is a pseudo-port of ASP.NET MVC 3ish to PHP. I wrote it
because I wanted to. It supports model binding, action filters,
routing and all of the other good(ish) stuff about ASP.NET MVC.

It was more of an academic exercise than anything useful,
but it actually does work pretty well.

## Installation
Use composer:

```json
{
  "require": {
    "tmont/facilius": "1.1.0"
  }
}
```

Facilius uses PSR-4 autoloading, facilitated by composer. So the following
will set everything up properly:

```php
require_once 'vendor/autoload.php';
```

## Usage
There is an example hello world app in [example/](./example). It
probably works. You can test it by running `(cd example && php -S localhost:8000)`
and visiting [http://localhost:8000/](http://localhost:8000/) in your browser.

Take a look at [example/index.php](./example/index.php) to see how to set up the app.

## Development
```bash
git clone git@github.com:tmont/facilius.git
cd facilius

# run tests
ant test

# generate code coverage in build/coverage
ant coverage
```
