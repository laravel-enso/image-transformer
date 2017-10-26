<?php

namespace LaravelEnso\ImageTransformer\Classes;

use Illuminate\Http\UploadedFile;
use ImageOptimizer\OptimizerFactory;

class ImageTransformer
{
    private const SUPPORTED_MIME_TYPES = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];
    private $files;

    public function __construct(array $files)
    {
        $this->files = $files;
    }

    public function optimize()
    {
        foreach ($this->files as $file) {
            $this->optimizeImage($file);
        }

        return $this;
    }

    public function resize(int $width, int $height)
    {
        if ($this->extensionIsMissing()) {
            \Log::warning(
                __('Please install php-gd or php-imagick extesion in to use the resize function')
            );

            return $this;
        }

        foreach ($this->files as $file) {
            $this->resizeImage($file, $width, $height);
        }

        return $this;
    }

    private function optimizeImage(UploadedFile $file)
    {
        if ($this->fileTypeIsNotSupported($file)) {
            return false;
        }

        $optimizer = (new OptimizerFactory())->get();
        $optimizer->optimize($file->getRealPath());
    }

    private function resizeImage(UploadedFile $file, int $width, int $height)
    {
        if ($this->fileTypeIsNotSupported($file)) {
            return false;
        }

        $image = \Image::make($file->getRealPath());

        if ($image->width() > $width) {
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        if ($image->height() > $height) {
            $image->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $image->save($file->getRealPath());
    }

    private function fileTypeIsNotSupported(UploadedFile $file)
    {
        $mimes = implode(',', self::SUPPORTED_MIME_TYPES);
        $validator = \Validator::make(['file' => $file], ['file' => 'image|mimetypes:'.$mimes]);

        return $validator->fails();
    }

    private function extensionIsMissing()
    {
        return !extension_loaded('gd') && !extension_loaded('imagick');
    }
}
