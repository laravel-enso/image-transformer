<?php

namespace LaravelEnso\ImageTransformer\Classes;

use Illuminate\Http\UploadedFile;
use ImageOptimizer\OptimizerFactory;

class ImageTransformer
{
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
        $validator = \Validator::make(['file' => $file], ['file' => 'image']);

        if ($validator->fails()) {
            return false;
        }

        $optimizer = (new OptimizerFactory())->get();
        $optimizer->optimize($file->getRealPath());
    }

    private function resizeImage(UploadedFile $file, int $width, int $height)
    {
        if ($validator = $this->fileIsInvalid($file)) {
            throw new \EnsoException(__('Trying to resize an invalid file').': '.$file->getRealPath());
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

    private function fileIsInvalid(UploadedFile $file)
    {
        $validator = \Validator::make(['file' => $file], ['file' => 'image']);

        if ($validator->fails()) {
            throw new \EnsoException($validator->errors()->first('file'));
        }
    }

    private function extensionIsMissing()
    {
        return !extension_loaded('gd') && !extension_loaded('imagick');
    }
}
