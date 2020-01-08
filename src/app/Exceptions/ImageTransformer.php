<?php

namespace LaravelEnso\ImageTransformer\App\Exceptions;

use LaravelEnso\Helpers\App\Exceptions\EnsoException;

class ImageTransformer extends EnsoException
{
    public static function invalidFile($filename)
    {
        return new self(__('Invalid file :filename', ['filename' => $filename]));
    }

    public static function unsupportedType($filename)
    {
        return new self(__('File type not supported for :filename', ['filename' => $filename]));
    }
}
