<?php

namespace LaravelEnso\ImageTransformer\app\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use LaravelEnso\ImageTransformer\app\Exceptions\ImageTransformer as ImageTransformerException;
use LaravelEnso\ImageTransformer\app\Exceptions\MissingDependency as MissingDependencyException;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class ImageTransformer
{
    public const SupportedMimeTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];

    private $file;
    private $image;

    public function __construct(UploadedFile $file)
    {
        $this->validate($file);

        $this->file = $file;
    }

    public function optimize()
    {
        ImageOptimizer::optimize($this->file->getRealPath());

        return $this;
    }

    public function resize(int $width, int $height)
    {
        $image = $this->image();

        if ($image->width() > $width || $image->height() > $height) {
            $image->resize($width, $height);
        }

        $image->save($this->file->getRealPath());

        return $this;
    }

    public function width(int $width)
    {
        $image = $this->image();

        if ($image->width() > $width) {
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $image->save($this->file->getRealPath());

        return $this;
    }

    public function height(int $height)
    {
        $image = $this->image();

        if ($image->height() > $height) {
            $image->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $image->save($this->file->getRealPath());

        return $this;
    }

    private function validate($file)
    {
        if ($file instanceof UploadedFile && ! $file->isValid()) {
            throw ImageTransformerException::invalidFile($file->getClientOriginalName());
        }

        $mimes = implode(',', self::SupportedMimeTypes);

        $validator = \Validator::make(
            ['file' => $file],
            ['file' => 'image|mimetypes:'.$mimes]
        );

        if ($validator->fails()) {
            throw ImageTransformerException::unsupportedType($file->getClientOriginalName());
        }
    }

    private function image()
    {
        if (! isset($this->image)) {
            $this->checkIfExtensionIsLoaded();
            $this->image = Image::make($this->file->getRealPath());
        }

        return $this->image;
    }

    private function checkIfExtensionIsLoaded()
    {
        if (! extension_loaded('gd') && ! extension_loaded('imagick')) {
            throw MissingDependencyException::extension();
        }
    }
}
