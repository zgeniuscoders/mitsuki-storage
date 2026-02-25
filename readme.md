# Mitsuki Storage

**Mitsuki Storage** is a flexible, framework-agnostic PHP library for managing file storage and structured records. Originally built for the Mitsuki framework, it provides a clean and secure abstraction over the Symfony Filesystem component to handle uploads, persistence, and file management in any PHP application.

---

## 🚀 Features

* **Secure Uploads**: Automatic unique filename generation to prevent collisions and overwrites.
* **Fluent Directory Management**: Automatically handles recursive directory creation.
* **Standardized Exceptions**: Robust error handling with custom `FileException` and `FileNotFoundException`.
* **Framework Agnostic**: Integration-ready for Laravel, Symfony, Slim, or any vanilla PHP project.
* **Fully Tested**: High-quality code base tested with Pest PHP.

---

## 📦 Installation

Install the package via Composer:

```bash
composer require mitsuki/storage

```

---

## 🛠 Usage

### Initialization

To start, you need a base directory where files will be stored and an instance of Symfony's `Filesystem`.

```php
use Mitsuki\Storage\Storage;
use Symfony\Component\Filesystem\Filesystem;

$baseDir = __DIR__ . '/storage';
$storage = new Storage(new Filesystem(), $baseDir);

```

### Storing an Uploaded File

The `store` method expects a `Symfony\Component\HttpFoundation\File\UploadedFile` (standard in many frameworks).

```php
try {
    // Stores file in storage/avatars/user-1/ with a secure unique name
    $absolutePath = $storage->store('avatars/user-1', $uploadedFile);
    
    echo "File saved at: " . $absolutePath;
} catch (FileException $e) {
    echo "Upload failed: " . $e->getMessage();
}

```

### Retrieving and Checking Files

```php
// Get the absolute path of a relative file
try {
    $path = $storage->getFile('avatars/user-1/photo.jpg');
} catch (FileNotFoundException $e) {
    // Handle error
}

// Simple existence check
if ($storage->exists('documents/report.pdf')) {
    // Logic here...
}

```

### Deleting Files

```php
try {
    $storage->delete('temp/old-file.txt');
} catch (FileNotFoundException $e) {
    // File not found
}

```

---

## 🧪 Testing

The library is tested using [Pest PHP](https://pestphp.com).

```bash
# Install development dependencies
composer install

# Run the test suite
vendor/bin/pest

```

---

## 📄 API Reference

### `Storage` Class

| Method | Argument | Description |
| --- | --- | --- |
| `store()` | `string $path, UploadedFile $file` | Validates, secures, and moves an upload. Returns full path. |
| `getFile()` | `string $path` | Resolves a relative path to an absolute path. |
| `exists()` | `string $path` | Returns `true` if the path exists. |
| `createDir()` | `string $path` | Recursively creates directories. |
| `delete()` | `string $path` | Removes a file or directory. |

---

## 👤 Author

**Zgenius Matondo**

* GitHub: [@zgeniusecoders](https://www.google.com/search?q=https://github.com/zgeniusecoders)
* Email: [zgeniusecoders@gmail.com](mailto:zgeniusecoders@gmail.com)

---

## ⚖️ License

This project is licensed under the MIT License - see the [LICENSE](https://www.google.com/search?q=LICENSE) file for details.

---