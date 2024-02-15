<?php

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

    public function upload(UploadedFile $file): string
    {
        $imageName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $imageSlug = $this->slugger->slug($imageName);
        $newImageName = $imageSlug.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $newImageName);
        } catch (FileException $e) {
            // TODO::handle execption
        }

        return $newImageName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
