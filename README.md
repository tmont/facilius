# Facilius
This is a pseudo-port of ASP.NET MVC 3ish to PHP. I wrote it
because I wanted to. It supports model binding, action filters,
routing and all of the other good(ish) stuff about ASP.NET MVC.

It was more of an academic exercise than anything useful,
but it actually does work pretty well.

## Usage
There is an example hello world app in [example/](./example). It
probably works. You can test it by running `(cd example && php -S localhost:8000)`
and visiting [http://localhost:8000/](http://localhost:8000/) in your browser.

## Development
```bash
git clone git@github.com:tmont/facilius.git
cd facilius

# run tests
ant test

# generate code coverage in build/coverage
ant coverage
```
