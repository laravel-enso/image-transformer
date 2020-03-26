<?php

namespace LaravelEnso\ImageTransformer\App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image as Facade;
use Intervention\Image\Image;
use LaravelEnso\ImageTransformer\App\Exceptions\Dependency;
use LaravelEnso\ImageTransformer\App\Exceptions\File;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class ImageTransformer
{
    public const SupportedMimeTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];

    private UploadedFile $file;
    private Image $image;

    public function __construct(UploadedFile $file)
    {
        $this->validate($file);

        $this->file = $file;
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

    private function validate(UploadedFile $file): void
    {
        if (! $file->isValid()) {
            throw File::invalid($file);
        }

        $mimes = implode(',', self::SupportedMimeTypes);

        $validator = Validator::make(
            ['file' => $file],
            ['file' => "image|mimetypes:{$mimes}"]
        );

        if ($validator->fails()) {
            throw File::notSupported($file);
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
