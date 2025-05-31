<?php

namespace SPHP\Core\Traits;

trait CacheHandlerTrait
{
    protected bool $useCache = false;
    protected int $cacheTtl = 300; // 5 minutos padrão
    protected ?string $cacheKey = null;

    public function cache(int $ttl = 300): static
    {
        $this->useCache = true;
        $this->cacheTtl = $ttl;
        return $this;
    }

    protected function makeCacheKey(string $prefix = ''): string
    {
        $baseKey = $prefix . '_' . md5(json_encode([
            'table' => $this->getTable(),
            'query' => $this->compiledSql ?? null,
            'bindings' => $this->bindings ?? [],
        ]));

        return 'cache_' . $baseKey;
    }

    protected function remember(string $prefix, callable $callback)
    {
        $key = $this->makeCacheKey($prefix);

        if (!isset($_SESSION['_model_cache'])) {
            $_SESSION['_model_cache'] = [];
        }

        $now = time();
        $cached = $_SESSION['_model_cache'][$key] ?? null;

        if ($cached && $cached['expires'] > $now) {
            return $cached['data'];
        }

        $result = $callback();

        $_SESSION['_model_cache'][$key] = [
            'data' => $result,
            'expires' => $now + $this->cacheTtl,
        ];

        return $result;
    }

    protected function clearCache(string $prefix = ''): void
    {
        $key = $this->makeCacheKey($prefix);
        unset($_SESSION['_model_cache'][$key]);
    }
}
