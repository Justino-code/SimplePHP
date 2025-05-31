<?php
namespace SPHP\Support;

class CacheHandler
{
    protected string $cachePath;
    protected int $ttl;

    public function __construct(string $cachePath, int $ttl = 60)
    {
        $this->cachePath = $cachePath;
        $this->ttl = $ttl;
    }

    public function get(string $key): array|false
    {
        $file = "{$this->cachePath}/{$key}.cache";
        if (file_exists($file) && filemtime($file) + $this->ttl > time()) {
            return unserialize(file_get_contents($file));
        }
        return false;
    }

    public function set(string $key, array $data): void
    {
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0777, true);
        }
        file_put_contents("{$this->cachePath}/{$key}.cache", serialize($data));
    }

    public function clear(): void
    {
        foreach (glob("{$this->cachePath}/*.cache") as $file) {
            unlink($file);
        }
    }
}
