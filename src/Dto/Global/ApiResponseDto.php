<?php

namespace Twix\Dto\Global;

/**
 * Class ApiResponseDto
 */
class ApiResponseDto
{
    /**
     * @var bool
     */
    private $success;

    /**
     * @var OrderDto | ErrorDto
     */
    private $data;

    /**
     * @var string|null
     */
    private $message;

    /**
     * @var int|null
     */
    private $code;
    /**
     * @var int|null
     */
    private $count;

    /**
     * @param array $response
     * @param string|null $successDtoClass DTO class for success response
     * @param string|null $errorDtoClass DTO class for error response
     */
    public function __construct(array $response, ?string $successDtoClass = null, ?string $errorDtoClass = null)
    {
        $this->code = $response['code'] ?? null;
        $this->success = isset($response['success']) && $response['success'] || $this->code == 200 ??  false;
        $this->message = $response['message'] ?? null;

        $rawData = $response['data'] ?? $response;
        $this->count = $rawData['count'] ?? $rawData['total'] ?? null;

        if (isset($rawData['code'])) {
            unset($rawData['code']);
        }

        if (isset($rawData['count'])) {
            unset($rawData['count']);
        }

        if (isset($rawData['total'])) {
            unset($rawData['total']);
        }
        if (isset($rawData['success'])) {
            unset($rawData['success']);
        }

        if (!$this->success && $errorDtoClass) {
            $this->data = $this->transformToDto($rawData, $errorDtoClass);
        } elseif ($this->success && $successDtoClass) {
            $this->data = $this->transformToDto($rawData, $successDtoClass);
        } else {
            $this->data = $rawData;
        }

    }

    /**
     * Transform raw data to DTO.
     *
     * @param mixed $data
     * @param string $dtoClass
     * @return mixed
     */
    private function transformToDto($data, string $dtoClass)
    {
        if (empty($data)) {
            return null;
        }

        if (is_array($data) && isset($data[0]) && is_array($data[0])) {
            return array_map(function($item) use ($dtoClass) {
                return $dtoClass::fromArray($item);
            }, $data);
        }

        if (is_array($data)) {
            return $dtoClass::fromArray($data);
        }

        return $data;
    }

    /**
     * Convert DTO to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [
            'success' => $this->success,
            'code' => $this->code,
        ];

        if ($this->message !== null) {
            $result['message'] = $this->message;
        }

        if ($this->count !== null) {
            $result['count'] = $this->count;
        }

        $result['data'] = $this->dataToArray($this->data);

        return $result;
    }

    /**
     * Convert data to array.
     *
     * @param mixed $data
     * @return mixed
     */
    private function dataToArray($data)
    {
        if ($data === null) {
            return null;
        }

        if (is_object($data) && method_exists($data, 'toArray')) {
            return $data->toArray();
        }

        if (is_array($data)) {
            return array_map(function($item) {
                if (is_object($item) && method_exists($item, 'toArray')) {
                    return $item->toArray();
                }
                return $item;
            }, $data);
        }

        return $data;
    }

    /**
     * Check if response is successful.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success === true;
    }

    /**
     * Check if response is error.
     *
     * @return bool
     */
    public function isError(): bool
    {
        return $this->success === false;
    }

    public function getMessage(): ?string { return $this->message; }
    public function getCode(): ?int { return $this->code; }
    public function getMeta(): ?array { return $this->meta; }
    public function getData() { return $this->data; }
}
