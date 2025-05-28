<?php

namespace Src;

/**
 * Classe para manipulação de arquivos enviados via formulário (upload).
 */
class UploadedFile
{
    private string $originalName;
    private string $mimeType;
    private string $tempPath;
    private int $size;
    private int $error;

    /**
     * @param array $file Dados do arquivo enviado ($_FILES['campo']).
     */
    public function __construct(array $file)
    {
        $this->originalName = $file['name'];
        $this->mimeType = $file['type'];
        $this->tempPath = $file['tmp_name'];
        $this->size = $file['size'];
        $this->error = $file['error'];
    }

    /**
     * Retorna o nome original do arquivo.
     * @return string
     */
    public function getClientOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * Retorna o tipo MIME do arquivo.
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Retorna o tamanho do arquivo em bytes.
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Retorna a extensão do arquivo com base no nome original.
     * @return string
     */
    public function getExtension(): string
    {
        return pathinfo($this->originalName, PATHINFO_EXTENSION);
    }

    /**
     * Move o arquivo para o diretório de destino.
     *
     * @param string $directory Diretório de destino.
     * @param string $name Nome com o qual o arquivo será salvo.
     * @return bool
     * @throws \RuntimeException Se o diretório não puder ser criado ou não for gravável.
     */
    public function move(string $directory, string $name): bool
    {
        if (!is_dir($directory)) {
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

    /**
     * Verifica se o upload foi bem-sucedido.
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->error === UPLOAD_ERR_OK;
    }
}
