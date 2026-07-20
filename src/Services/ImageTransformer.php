<?php

namespace LaravelEnso\ImageTransformer\Services;

use Illuminate\Image\Image;
use Illuminate\Support\Facades\File as Filesystem;
use Illuminate\Support\Facades\Image as Facade;
use Illuminate\Support\Facades\Validator;
use LaravelEnso\ImageTransformer\Exceptions\Dependency;
use LaravelEnso\ImageTransformer\Exceptions\File as Exception;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageTransformer
{
    public const SupportedMimeTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];
    private const DefaultQuality = 75;

    private Image $image;
    private int $imageWidth;
    private int $imageHeight;

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
        $this->width($width);
        $this->height($height);

        return $this;
    }

    public function upscaleTo(int $width, int $height): self
    {
        $image = $this->image();
        $width = max(1, $width);
        $height = max(1, $height);
        $scale = max(
            $width / $this->imageWidth,
            $height / $this->imageHeight,
            1,
        );

        if ($scale > 1) {
            $this->imageWidth = (int) ceil($this->imageWidth * $scale);
            $this->imageHeight = (int) ceil($this->imageHeight * $scale);
            $this->image = $image->resize(
                width: $this->imageWidth,
                height: $this->imageHeight,
            );
        }

        return $this;
    }

    public function toJpeg(int $quality = 85): string
    {
        return $this->image()
            ->toJpeg()
            ->quality($quality)
            ->toBytes();
    }

    public function width(int $width): self
    {
        $image = $this->image();
        $width = max(1, $width);

        if ($this->imageWidth > $width) {
            $this->imageHeight = max(
                1,
                (int) round($this->imageHeight * $width / $this->imageWidth),
            );
            $this->imageWidth = $width;
            $this->image = $image->scale(width: $width);
        }

        $this->save();

        return $this;
    }

    public function height(int $height): self
    {
        $image = $this->image();
        $height = max(1, $height);

        if ($this->imageHeight > $height) {
            $this->imageWidth = max(
                1,
                (int) round($this->imageWidth * $height / $this->imageHeight),
            );
            $this->imageHeight = $height;
            $this->image = $image->scale(height: $height);
        }

        $this->save();

        return $this;
    }

    private function validate(File $file): void
    {
        if ($file instanceof UploadedFile && !$file->isValid()) {
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
        if (!isset($this->image)) {
            $this->checkIfExtensionIsLoaded();
            $this->image = Facade::fromPath($this->file->getRealPath());
            [$this->imageWidth, $this->imageHeight] = $this->image->dimensions();
        }

        return $this->image;
    }

    private function save(): void
    {
        Filesystem::put(
            $this->file->getRealPath(),
            $this->image->quality(self::DefaultQuality)->toBytes(),
        );
    }

    private function checkIfExtensionIsLoaded()
    {
        if (!extension_loaded('gd') && !extension_loaded('imagick')) {
            throw Dependency::missing();
        }
    }
}
