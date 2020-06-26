<?php

namespace LaravelEnso\ImageTransformer\Exceptions;

use LaravelEnso\Helpers\Exceptions\EnsoException;

class Dependency extends EnsoException
{
    public static function missing()
    {
        return new self(__(
            'Extension missing. Please install php-gd or php-imagick to use the resize function'
        ));
    }
}
