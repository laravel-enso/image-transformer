# Image Transformer
[![License](https://poser.pugx.org/laravel-enso/imagetransformer/license)](https://https://packagist.org/packages/laravel-enso/imagetransformer)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/49a59dad1899460fa451510ef96307bb)](https://www.codacy.com/app/laravel-enso/ImageTransformer?utm_source=github.com&utm_medium=referral&utm_content=laravel-enso/ImageTransformer&utm_campaign=badger)
[![StyleCI](https://styleci.io/repos/96102464/shield?branch=master)](https://styleci.io/repos/96102464)
[![Total Downloads](https://poser.pugx.org/laravel-enso/imagetransformer/downloads)](https://packagist.org/packages/laravel-enso/imagetransformer)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/imagetransformer/version)](https://packagist.org/packages/laravel-enso/imagetransformer)


Image transformer dependency for [Laravel Enso](https://github.com/laravel-enso/Enso).

### Details

* handles image optimization, using the [Image Optimizer](https://github.com/psliwa/image-optimizer) library
* handles image cropping, using the [Intervention Image](https://github.com/intervention/image) library

### Installations 

In order for the optimization to work, you need to have the following packages installed:
* pngquant 
* gifsicle 
* jpegoptim

On Linux, you can do that with: `sudo apt-get install pngquant gifsicle jpegoptim`

### Note

This package is included in [Laravel Enso Core](https://github.com/laravel-enso/Core).

### Contributions

are welcome