<?php

namespace Src;

class UploadedFile
{
    private $originalName;
    private $mimeType;
    private $tempPath;
    private $size;
    private $error;

    public function __construct(array $file)
    {
        $this->originalName = $file['name'];
        $this->mimeType = $file['type'];
        $this->tempPath = $file['tmp_name'];
        $this->size = $file['size'];
        $this->error = $file['error'];
    }

    public function getClientOriginalName(): string
    {
        return $this->originalName;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getExtension(): string
    {
        return pathinfo($this->originalName, PATHINFO_EXTENSION);
    }

    public function move(string $directory, string $name): bool
{
    if (!is_dir($directory)) {
        // Cria a pasta com permissões 0755, permitindo criar subpastas se necessário
        if (!mkdir($directory, 0755, true)) {
            throw new \RuntimeException("Não foi possível criar o diretório: $directory");
        }
    }

    $destination = rtrim($directory, '/') . '/' . $name;

    if (!is_writable($directory)) {
        throw new \RuntimeException("O diretório não tem permissão de escrita: $directory");
    }

    return move_uploaded_file($this->tempPath, $destination);
}


    public function isValid(): bool
    {
        return $this->error === UPLOAD_ERR_OK;
    }
}
