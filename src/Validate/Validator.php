<?php

namespace SPHP\Validate;

use DateTime;

/**
 * Classe utilitária para validação e sanitização de dados.
 */
class Validator
{
    /**
     * @var array Lista de erros encontrados na validação.
     */
    private static array $errors = [];

    /**
     * Valida os dados com base nas regras especificadas.
     *
     * @param array $data Dados brutos (ex: $_POST).
     * @param array $rules Regras de validação.
     * @return array|false Retorna dados sanitizados ou false em caso de erro.
     */
    public static function make(array $data, array $rules): array|false
    {
        self::$errors = [];
        $sanitized = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? '';
            $fieldRules = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);

            $nullable = in_array('nullable', $fieldRules, true);
            if ($nullable && trim((string)$value) === '') {
                $sanitized[$field] = '';
                continue;
            }

            foreach ($fieldRules as $rule) {
                [$ruleName, $param] = array_pad(explode(':', $rule, 2), 2, null);

                // Regras de validação omitidas por brevidade (já estão no código anterior)
                // ...
            }

            $sanitized[$field] = is_string($value) ? self::sanitizeString($value) : $value;
        }

        return self::hasErrors() ? false : $sanitized;
    }

    /**
     * Verifica se há erros de validação.
     * @return bool
     */
    public static function hasErrors(): bool
    {
        return !empty(self::$errors);
    }

    /**
     * Retorna todos os erros de validação.
     * @return array
     */
    public static function getErrors(): array
    {
        return self::$errors;
    }

    /**
     * Retorna a primeira mensagem de erro (string).
     * @return string|false
     */
    public static function getError(): string|false
    {
        foreach (self::$errors as $fieldErrors) {
            if (is_array($fieldErrors) && count($fieldErrors) > 0) {
                return $fieldErrors[0];
            }
        }
        return false;
    }

    /**
     * Retorna o primeiro erro como array associativo.
     * @return array
     */
    public static function getFirstError(): array
    {
        $key = array_keys(self::$errors);
        $errors = array_shift(self::$errors);
        return [$key[0] => $errors[0]];
    }

    /**
     * Retorna apenas a mensagem do primeiro erro.
     * @return string|null
     */
    public static function firstError(): ?string
    {
        return array_values(array_map('current', self::$errors))[0] ?? null;
    }

    /**
     * Adiciona uma mensagem de erro ao campo especificado.
     * @param string $field
     * @param string $message
     */
    private static function addError(string $field, string $message): void
    {
        self::$errors[$field][] = $message;
        $_SESSION['validation_errors'] = self::$errors;
    }

    // Métodos de sanitização e validação omitidos aqui (já estão no código anterior)
}
