<?php
namespace SPHP\Support;

class Sanitizer
{
    public static function clean(array $data): array
    {
        return array_map(fn($v) => is_string($v) ? htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8') : $v, $data);
    }
}
