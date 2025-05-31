<?php

namespace Src;

use \DateTime;

class Validator
{
    private static array $errors = [];

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

                switch ($ruleName) {
                    case 'required':
                        if (!self::isRequired($value)) {
                            self::addError($field, "O campo {$field} é obrigatório.");
                        }
                        break;

                    case 'email':
                        $value = self::sanitizeEmail($value);
                        if (!$value) {
                            self::addError($field, "O campo {$field} deve ser um e-mail válido.");
                        }
                        break;

                    case 'int':
                    case 'integer':
                        $value = (int) $value;
                        if (!self::isValidInt($value)) {
                            self::addError($field, "O campo {$field} deve ser um número inteiro.");
                        }
                        break;

                    case 'numeric':
                        if (!is_numeric($value)) {
                            self::addError($field, "O campo {$field} deve ser numérico.");
                        }
                        break;

                    case 'string':
                        if (!is_string($value)) {
                            self::addError($field, "O campo {$field} deve ser uma string.");
                        }
                        break;

                    case 'url':
                        $value = self::sanitizeUrl($value);
                        if (!self::isValidUrl($value)) {
                            self::addError($field, "O campo {$field} deve ser uma URL válida.");
                        }
                        break;

                    case 'min':
                        if (strlen((string)$value) < (int)$param) {
                            self::addError($field, "O campo {$field} deve ter no mínimo {$param} caracteres.");
                        }
                        break;

                    case 'max':
                        if (strlen((string)$value) > (int)$param) {
                            self::addError($field, "O campo {$field} deve ter no máximo {$param} caracteres.");
                        }
                        break;

                    case 'regex':
                        if (!preg_match($param, $value)) {
                            self::addError($field, "O campo {$field} não corresponde ao formato esperado.");
                        }
                        break;

                    case 'array':
                        if (!is_array($value)) {
                            self::addError($field, "O campo {$field} deve ser um array.");
                        }
                        break;

                    case 'in':
                        $options = explode(',', $param);
                        if (!in_array($value, $options, true)) {
                            self::addError($field, "O campo {$field} deve ser um dos seguintes valores: " . implode(', ', $options) . '.');
                        }
                        break;

                    case 'confirmed':
                        $confirmationField = $field . '_confirmation';
                        $confirmedValue = $data[$confirmationField] ?? null;
                        if ($confirmedValue === null || $value !== $confirmedValue) {
                            self::addError($field, "O campo {$field} não corresponde à confirmação.");
                        }
                        break;
	
			case 'different':
			    $otherField = $param;
			    $otherValue = $data[$otherField] ?? null;
			    if ($value === $otherValue) {
				self::addError($field, "O campo {$field} deve ser diferente de {$otherField}.");
			    }
break;
/*case 'date':
    $date = DateTime::createFromFormat('Y-m-d', $value);
    $errors = DateTime::getLastErrors();

    if (!$date || $errors['warning_count'] > 0 || $errors['error_count'] > 0) {
        self::addError($field, "O campo {$field} deve conter uma data válida no formato YYYY-MM-DD.");
    }
    break;
			   case 'not_past':
    $parts = explode(',', $param);
    $dateField = $parts[0] ?? null;
    $timeField = $parts[1] ?? null;

    $dateStr = trim($data[$dateField] ?? '');
    $timeStr = trim($data[$timeField] ?? '');

    $datetimeStr = "{$dateStr} {$timeStr}";
    $datetime = DateTime::createFromFormat('Y-m-d H:i', $datetimeStr);

    // Verifica erros de parsing
    $errors = DateTime::getLastErrors();

    if (!$datetime || $errors['warning_count'] > 0 || $errors['error_count'] > 0) {
        self::addError($field, "Data ou hora inválida.");
    } elseif ($datetime->getTimestamp() < time()) {
        self::addError($field, "A data e hora não podem estar no passado.");
    }
    break;*/


                    case 'nullable':
                        // já tratado antes
                        break;
                }
            }

            $sanitized[$field] = is_string($value) ? self::sanitizeString($value) : $value;
        }

        return self::hasErrors() ? false : $sanitized;
    }

    public static function hasErrors(): bool
    {
        return !empty(self::$errors);
    }

    public static function getErrors(): array
    {
        return self::$errors;
    }

    public static function getError(): string | bool
    {
        foreach (self::$errors as $fieldErrors) {
            if (is_array($fieldErrors) && count($fieldErrors) > 0) {
                return $fieldErrors[0];
            }
        }
        return false;
    }

    public static function getFirstError(): array
    {
        $key = array_keys(self::$errors);
        $errors = array_shift(self::$errors);
        return [$key[0] => $errors[0]];
    }

    public static function firstError(): ?string
    {
        return array_values(array_map('current', self::$errors))[0] ?? null;
    }

    private static function addError(string $field, string $message): void
    {
        self::$errors[$field][] = $message;
        $_SESSION['validation_errors'] = self::$errors;
    }

    public static function sanitizeString(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function sanitizeEmail(string $input): ?string
    {
        $email = filter_var(trim($input), FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    public static function sanitizeInt(string|int $input): int
    {
        return (int) filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function sanitizeUrl(string $input): string
    {
        return filter_var(trim($input), FILTER_SANITIZE_URL);
    }

    public static function isValidInt(int|string $input): bool
    {
        return filter_var($input, FILTER_VALIDATE_INT) !== false;
    }

    public static function isValidEmail(string $input): bool
    {
        return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function isValidUrl(string $input): bool
    {
        return filter_var($input, FILTER_VALIDATE_URL) !== false;
    }

    public static function isRequired(mixed $input): bool
    {
        return !(is_null($input) || trim((string)$input) === '');
    }
}
