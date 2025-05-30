<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ResizeGalleryImages extends Command
{
    protected $signature = 'gallery:resize-images';

    protected $description = 'Traverse gallery folder and resize all images to max 750x1000 while preserving aspect ratio';

    public function handle()
    {
        $basePath = storage_path('app/public/images/gallery');
        $files = File::allFiles($basePath);
        $this->info('Found '.count($files).' files. Processing...');

        $manager = new ImageManager(new Driver);

        foreach ($files as $file) {
            $path = $file->getPathname();
            $ext = strtolower($file->getExtension());

            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $this->line("Skipping non-image: {$path}");

                continue;
            }

            try {
                $image = $manager->read($path);

                $width = $image->width();
                $height = $image->height();

                $maxWidth = 750;
                $maxHeight = 1000;

                // Compute new dimensions while maintaining aspect ratio
                $ratio = $width / $height;

                if ($width > $maxWidth || $height > $maxHeight) {
                    if ($width / $maxWidth > $height / $maxHeight) {
                        $newWidth = $maxWidth;
                        $newHeight = intval($maxWidth / $ratio);
                    } else {
                        $newHeight = $maxHeight;
                        $newWidth = intval($maxHeight * $ratio);
                    }

                    $image = $image->resize($newWidth, $newHeight);
                    $image->save($path, quality: 85);
                    $this->info("Resized: {$path} to {$newWidth}x{$newHeight}");
                } else {
                    $this->line("Skipping (already small): {$path}");
                }

            } catch (\Exception $e) {
                $this->error("Failed: {$path} — ".$e->getMessage());
            }
        }

        $this->info('Done!');
    }
}
