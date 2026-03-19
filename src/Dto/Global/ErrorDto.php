<?php

namespace Twix\Dto\Global;

/**
 * Class ErrorDto
 */
class ErrorDto
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var array|null
     */
    private $messages;

    /**
     * @var array|null
     */
    private $errors;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->status = $data['status'] ?? 'failure';
        $dto->messages = $data['messages'] ?? null;
        $dto->message = $data['message'] ?? null;
        $dto->errors = $data['errors'] ?? null;

        return $dto;
    }

    /**
     * Check if error has field errors.
     *
     * @return bool
     */
    public function hasFieldErrors(): bool
    {
        return !empty($this->messages) || !empty($this->errors);
    }

    /**
     * Get all field errors as flat array.
     *
     * @return array
     */
    public function getFieldErrors(): array
    {
        $errors = [];

        if (!empty($this->messages)) {
            foreach ($this->messages as $field => $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    $errors[] = "{$field}: {$error}";
                }
            }
        }

        if (!empty($this->errors)) {
            foreach ($this->errors as $field => $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    $errors[] = "{$field}: {$error}";
                }
            }
        }

        return $errors;
    }

    /**
     * Get errors for specific field.
     *
     * @param string $field
     * @return array
     */
    public function getFieldErrorsFor(string $field): array
    {
        $errors = [];

        if (isset($this->messages[$field]) && is_array($this->messages[$field])) {
            $errors = array_merge($errors, $this->messages[$field]);
        }

        if (isset($this->errors[$field]) && is_array($this->errors[$field])) {
            $errors = array_merge($errors, $this->errors[$field]);
        }

        return $errors;
    }

    /**
     * Get first error message.
     *
     * @return string|null
     */
    public function getFirstError(): ?string
    {
        $errors = $this->getFieldErrors();
        return !empty($errors) ? $errors[0] : $this->message;
    }

    /**
     * Convert to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'status' => $this->status,
            'code' => $this->code,
        ];

        if ($this->messages !== null) {
            $data['messages'] = $this->messages;
        }

        if ($this->errors !== null) {
            $data['errors'] = $this->errors;
        }

        if ($this->message !== null) {
            $data['message'] = $this->message;
        }

        return $data;
    }

    // Геттеры
    public function getStatus(): string { return $this->status; }
    public function getMessages(): ?array { return $this->messages; }
    public function getErrors(): ?array { return $this->errors; }
    public function getCode(): ?int { return $this->code; }
    public function getMessage(): ?string { return $this->message; }

    /**
     * Check if error is validation error.
     *
     * @return bool
     */
    public function isValidationError(): bool
    {
        return $this->code === 422 || !empty($this->messages) || !empty($this->errors);
    }

    /**
     * Check if error is authentication error.
     *
     * @return bool
     */
    public function isAuthError(): bool
    {
        return in_array($this->code, [401, 403]);
    }

    /**
     * Check if error is not found error.
     *
     * @return bool
     */
    public function isNotFoundError(): bool
    {
        return $this->code === 404;
    }

    /**
     * Check if error is server error.
     *
     * @return bool
     */
    public function isServerError(): bool
    {
        return $this->code >= 500;
    }
}
