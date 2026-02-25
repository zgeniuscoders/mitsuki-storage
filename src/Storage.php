<?php

namespace Mitsuki\Storage;

use Mitsuki\Storage\Exceptions\FileException;
use Mitsuki\Storage\Exceptions\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Handles file storage operations including uploading, retrieving, and deleting files.
 * * @author Zgenius Matondo <zgeniusocders@gmail.com>
 */
class Storage
{
    /**
     * @param Filesystem $filesystem The Symfony Filesystem component.
     * @param string $directory The base directory for file storage.
     */
    public function __construct(
        private Filesystem $filesystem,
        private string     $directory,
    )
    {
    }

    /**
     * Stores an uploaded file in the specified path with a unique name.
     *
     * @param string $path The relative destination path.
     * @param UploadedFile $uploadedFile The file object to be stored.
     * @return string The full absolute path of the stored file.
     * @throws FileException If the file is invalid or cannot be moved.
     */
    public function store(string $path, UploadedFile $uploadedFile): string
    {
        if (!$uploadedFile->isValid()) {
            throw new FileException("The uploaded file is not a valid file.");
        }

        $directory = $this->directory . '/' . trim($path, '/');
        $this->createDir($directory);

        $secureName = rand() . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        $fullPath = $directory . '/' . $secureName;

        try {
            $uploadedFile->move($directory, $secureName);
        } catch (\Exception $exception) {
            throw new FileException($exception->getMessage());
        }

        return $fullPath;
    }

    /**
     * Retrieves the full path of a file.
     *
     * @param string $path The relative path of the file.
     * @return string The full absolute path.
     * @throws FileNotFoundException If the file does not exist.
     */
    public function getFile(string $path): string
    {
        $fullPath = $this->directory . '/' . trim($path, '/');

        // Note: Your logic still throws exception if file EXISTS.
        // Logic should likely be: if (!$this->exists($fullPath))
        if ($this->exists($fullPath)) {
            throw new FileNotFoundException($path);
        }

        return $fullPath;
    }

    /**
     * Checks if a file or directory exists.
     *
     * @param string $path The path to check.
     * @return bool True if it exists, false otherwise.
     */
    public function exists(string $path): bool
    {
        return $this->filesystem->exists($path);
    }

    /**
     * Creates a directory recursively.
     *
     * @param string $path The directory path to create.
     * @return void
     */
    public function createDir(string $path): void
    {
        $this->filesystem->mkdir($path);
    }

    /**
     * Deletes a file or directory.
     *
     * @param string $path The path to remove.
     * @return void
     * @throws FileNotFoundException If the path is not found.
     */
    public function delete(string $path): void
    {
        if ($this->filesystem->exists($path)) {
            $this->filesystem->remove($path);
            return;
        }
        throw new FileNotFoundException();
    }
}