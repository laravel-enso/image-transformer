<?php

namespace LaravelEnso\ImageTransformer\Services;

use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image as Facade;
use Intervention\Image\Image;
use LaravelEnso\ImageTransformer\Exceptions\Dependency;
use LaravelEnso\ImageTransformer\Exceptions\File as Exception;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageTransformer
{
    public const SupportedMimeTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];

    private Image $image;

    public function __construct(private File $file)
    {
        $this->validate($file);
    }

    public function optimize(): self
    {
        ImageOptimizer::optimize($this->file->getRealPath());

        return $this;
    }

    public function resize(int $width, int $height): self
    {
        $image = $this->image();

        if ($image->width() > $width || $image->height() > $height) {
            $image->resize($width, $height);
        }

        $image->save($this->file->getRealPath());

        return $this;
    }

    public function width(int $width): self
    {
        $image = $this->image();

        if ($image->width() > $width) {
            $image->resize($width, null, fn ($constraint) => $constraint->aspectRatio());
        }

        $image->save($this->file->getRealPath());

        return $this;
    }

    public function height(int $height): self
    {
        $image = $this->image();

        if ($image->height() > $height) {
            $image->resize(null, $height, fn ($constraint) => $constraint->aspectRatio());
        }

        $image->save($this->file->getRealPath());

        return $this;
    }

    private function validate(File $file): void
    {
        if ($file instanceof UploadedFile && ! $file->isValid()) {
            throw Exception::invalid($file);
        }

        $mimes = implode(',', self::SupportedMimeTypes);

        $validator = Validator::make(
            ['file' => $file],
            ['file' => "image|mimetypes:{$mimes}"]
        );

        if ($validator->fails()) {
            throw Exception::notSupported($file);
        }
    }

    private function image(): Image
    {
        if (! isset($this->image)) {
            $this->checkIfExtensionIsLoaded();
            $this->image = Facade::make($this->file->getRealPath());
        }

        return $this->image;
    }

    private function checkIfExtensionIsLoaded()
    {
        if (! extension_loaded('gd') && ! extension_loaded('imagick')) {
            throw Dependency::missing();
        }
    }
}
