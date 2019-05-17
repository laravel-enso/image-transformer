# Image Transformer

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/49a59dad1899460fa451510ef96307bb)](https://www.codacy.com/app/laravel-enso/ImageTransformer?utm_source=github.com&utm_medium=referral&utm_content=laravel-enso/ImageTransformer&utm_campaign=badger)
[![StyleCI](https://github.styleci.io/repos/96102464/shield?branch=master)](https://github.styleci.io/repos/96102464)
[![License](https://poser.pugx.org/laravel-enso/image-transformer/license)](https://packagist.org/packages/laravel-enso/image-transformer)
[![Total Downloads](https://poser.pugx.org/laravel-enso/image-transformer/downloads)](https://packagist.org/packages/laravel-enso/image-transformer)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/image-transformer/version)](https://packagist.org/packages/laravel-enso/image-transformer)

Image transformer dependency for [Laravel Enso](https://github.com/laravel-enso/Enso).

This package can work independently of the [Enso](https://github.com/laravel-enso/Enso) ecosystem.

For live examples and demos, you may visit [laravel-enso.com](https://www.laravel-enso.com)

## Installation

Comes pre-installed in Enso.

To install outside of Enso: `composer require laravel-enso/imagetransformer`

In order for the optimization to work, you need to have the following packages installed:
* pngquant
* gifsicle
* jpegoptim
* php7.1-gd or php-imagick

On Linux, you can do that with: `sudo apt-get install pngquant gifsicle jpegoptim php7.1-gd`

**IMPORTANT NOTE:** 

The underlying image processing libraries may use a lot of memory, 
especially if the processed files are large (for example, for an 8MB file, more than 128MB of memory might be used ),
so make sure to configure php accordingly and/or do `ini_set(‘memory_limit’, ‘256M’);`   

Failure to do so may result in silent errors if allotted memory is insufficient.

### Configuration & Usage

Be sure to check out the full documentation for this package available at [docs.laravel-enso.com](https://docs.laravel-enso.com/backend/image-transformer.html)

### Contributions

are welcome. Pull requests are great, but issues are good too.

### License

This package is released under the MIT license.
