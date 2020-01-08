<?php

namespace LaravelEnso\ImageTransformer\App\Exceptions;

use LaravelEnso\Helpers\App\Exceptions\EnsoException;

class MissingDependency extends EnsoException
{
    public static function extension()
    {
        return new self(__(
            'Extension missing. Please install php-gd or php-imagick extension to use the resize function'
        ));
    }
}
