<!--h-->
# Image Transformer
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/49a59dad1899460fa451510ef96307bb)](https://www.codacy.com/app/laravel-enso/ImageTransformer?utm_source=github.com&utm_medium=referral&utm_content=laravel-enso/ImageTransformer&utm_campaign=badger)
[![StyleCI](https://styleci.io/repos/96102464/shield?branch=master)](https://styleci.io/repos/96102464)
[![License](https://poser.pugx.org/laravel-enso/imagetransformer/license)](https://https://packagist.org/packages/laravel-enso/imagetransformer)
[![Total Downloads](https://poser.pugx.org/laravel-enso/imagetransformer/downloads)](https://packagist.org/packages/laravel-enso/imagetransformer)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/imagetransformer/version)](https://packagist.org/packages/laravel-enso/imagetransformer)
<!--/h-->


Image transformer dependency for [Laravel Enso](https://github.com/laravel-enso/Enso).

### Features

- handles image optimization, using the [Image Optimizer](https://github.com/psliwa/image-optimizer) library
- handles image cropping, using the [Intervention Image](https://github.com/intervention/image) library

### Under the Hood
- handles missing libraries gracefully, logging the fact but does not throw an error

### Installation

In order for the optimization to work, you need to have the following packages installed:
* pngquant
* gifsicle
* jpegoptim
* php7.1-gd or php-imagick

On Linux, you can do that with: `sudo apt-get install pngquant gifsicle jpegoptim php7.1-gd`

### Use
The `ImageTransformer` class has 2 public methods:
- `optimize`, which takes no argument and tries to optimize all the files given when building the class instance
- `resize`, which takes the target width and height and resizes all the files given when building the class instance 

### Notes

The [Laravel Enso Core](https://github.com/laravel-enso/Core) package comes with this package included.

<!--h-->
### Contributions

are welcome. Pull requests are great, but issues are good too.

### License

This package is released under the MIT license.
<!--/h-->