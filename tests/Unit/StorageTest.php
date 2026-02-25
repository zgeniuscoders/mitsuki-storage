<?php

use Mitsuki\Storage\Storage;
use Mitsuki\Storage\Exceptions\FileException;
use Mitsuki\Storage\Exceptions\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

beforeEach(function () {
    $this->filesystem = Mockery::mock(Filesystem::class);
    $this->baseDir = '/var/www/storage';
    $this->storage = new Storage($this->filesystem, $this->baseDir);
});

afterEach(function () {
    Mockery::close();
});

it('throws an exception if the uploaded file is invalid', function () {
    $file = Mockery::mock(UploadedFile::class);
    $file->shouldReceive('isValid')->once()->andReturn(false);

    $this->storage->store('avatars', $file);
})->throws(FileException::class, "The uploaded file is not a valid file.");

it('stores a file correctly and returns its full path', function () {
    $file = Mockery::mock(UploadedFile::class);

    $file->shouldReceive('isValid')->andReturn(true);
    $file->shouldReceive('guessExtension')->andReturn('png');
    $file->shouldReceive('move')->once();

    $this->filesystem->shouldReceive('mkdir')->once();

    $result = $this->storage->store('images', $file);

    expect($result)->toContain('/var/www/storage/images/')
        ->and($result)->toEndWith('.png');
});

### --- getFile() ---

it(/**
 * @throws FileNotFoundException
 */ 'returns the full path if the file exists', function () {
    $path = 'docs/test.pdf';
    $fullPath = '/var/www/storage/docs/test.pdf';

    $this->filesystem->shouldReceive('exists')->with($fullPath)->andReturn(false);

    expect($this->storage->getFile($path))->toBe($fullPath);
});

it(/**
 * @throws FileNotFoundException
 */ 'throws an exception if the file does not exist', function () {
    $path = 'missing.jpg';
    $fullPath = '/var/www/storage/missing.jpg';

    $this->filesystem->shouldReceive('exists')->with($fullPath)->andReturn(true);

    $this->storage->getFile($path);
})->throws(FileNotFoundException::class);

### --- delete() ---

it(/**
 * @throws FileNotFoundException
 */ 'removes the file if it exists', function () {
    $path = '/tmp/file.txt';

    $this->filesystem->shouldReceive('exists')->with($path)->andReturn(true);
    $this->filesystem->shouldReceive('remove')->with($path)->once();

    $this->storage->delete($path);
});

it(/**
 * @throws FileNotFoundException
 */ 'throws an exception when deleting a non-existent file', function () {
    $path = '/tmp/void.txt';

    $this->filesystem->shouldReceive('exists')->with($path)->andReturn(false);

    $this->storage->delete($path);
})->throws(FileNotFoundException::class);