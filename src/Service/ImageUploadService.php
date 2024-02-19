<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageUploadService
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger
    ) {
    }

    /**
     * @param UploadedFile $file
     * @return string
     * @throws FileException
     */
    public function upload(UploadedFile $file): string
    {
        $imageName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $imageSlug = $this->slugger->slug($imageName);
        $newImageName = $imageSlug.'-'.uniqid().'.'.$file->guessExtension();
        $file->move($this->targetDirectory, $newImageName);

        return $newImageName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
