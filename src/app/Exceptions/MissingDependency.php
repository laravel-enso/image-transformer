<?php

namespace LaravelEnso\ImageTransformer\app\Exceptions;

use LaravelEnso\Helpers\app\Exceptions\EnsoException;

class MissingDependency extends EnsoException
{
    public static function extension()
    {
        return new self(__(
            'Extension missing. Please install php-gd or php-imagick extension to use the resize function'
        ));
    }
}
