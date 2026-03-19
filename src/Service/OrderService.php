<?php

namespace Twix\Service;

use Twix\Dto\Global\ApiResponseDto;
use Twix\Dto\Merchant\CreateOrderDto;
use Twix\Dto\Global\ErrorDto;
use Twix\Dto\Global\OrderDto;
use Twix\Enum\OrderStatus;

class OrderService extends AbstractService
{
    /**
     * Get list of orders.
     *
     * @param OrderStatus|null $status Filter by status
     * @param int|null $perPage Items per page (max 100)
     * @return ApiResponseDto
     */
    public function list(?OrderStatus $status = null, ?int $perPage = null): ApiResponseDto
    {
        $query = [];
        if ($status !== null) {
            $query['status'] = $status->getValue();
        }
        if ($perPage !== null) {
            $perPage = min(100, max(1, $perPage));
            $query['per_page'] = $perPage;
        }

        $response = $this->request('GET', 'orders', ['query' => $query]);
        $data = $response['data'] ?? $response;

        foreach ($data as $orderData) {
            if (!empty($orderData['id'])) {
                $orders[] = OrderDto::fromArray($orderData);
            }
        }
        $orders['code'] = $response['code'] ?? null;
        return new ApiResponseDto($orders);
    }

    /**
     * Get order details.
     *
     * @param string $orderId Order UUID
     * @return ApiResponseDto
     */
    public function get(string $orderId): ApiResponseDto
    {
        $response = $this->request('GET', "orders/{$orderId}");

        return new ApiResponseDto($response, OrderDto::class, ErrorDto::class);
    }

    /**
     * Create a new order.
     *
     * @param CreateOrderDto $data Order data
     * @return ApiResponseDto
     */
    public function create(CreateOrderDto $data)
    {
        $response = $this->request('POST', 'orders', [
            'json' => $data->toArray(),
        ]);

        return new ApiResponseDto($response, OrderDto::class, ErrorDto::class);
    }

    /**
     * Upload receipt for order.
     *
     * @param string $orderId Order UUID
     * @param string|null $filePath Path to file (multipart)
     * @param string|null $fileUrl URL of receipt
     * @return ApiResponseDto
     */
    public function uploadReceipt(string $orderId, ?string $filePath = null, ?string $fileUrl = null): ApiResponseDto
    {
        if ($filePath === null && $fileUrl === null) {
            throw new \InvalidArgumentException('Either filePath or fileUrl must be provided');
        }

        $multipart = [];
        if ($filePath !== null) {
            if (!file_exists($filePath)) {
                throw new \InvalidArgumentException("File not found: {$filePath}");
            }

            $multipart[] = [
                'name' => 'receipt',
                'contents' => fopen($filePath, 'r'),
                'filename' => basename($filePath),
            ];
        } else {
            $multipart[] = [
                'name' => 'file_url',
                'contents' => $fileUrl,
            ];
        }

        $response = $this->request('POST', "orders/{$orderId}/receipt", [
            'multipart' => $multipart,
        ]);

        return new ApiResponseDto($response, OrderDto::class, ErrorDto::class);
    }

    /**
     * Replace receipt for order (in dispute/canceled status).
     *
     * @param string $orderId Order UUID
     * @param string|null $filePath Path to file
     * @param string|null $fileUrl URL of receipt
     * @return ApiResponseDto
     */
    public function replaceReceipt(string $orderId, ?string $filePath = null, ?string $fileUrl = null): ApiResponseDto
    {
        if ($filePath === null && $fileUrl === null) {
            throw new \InvalidArgumentException('Either filePath or fileUrl must be provided');
        }

        $multipart = [];
        if ($filePath !== null) {
            if (!file_exists($filePath)) {
                throw new \InvalidArgumentException("File not found: {$filePath}");
            }

            $multipart[] = [
                'name' => 'file',
                'contents' => fopen($filePath, 'r'),
                'filename' => basename($filePath),
            ];
        } else {
            $multipart[] = [
                'name' => 'file_url',
                'contents' => $fileUrl,
            ];
        }

        $response = $this->request('POST', "orders/{$orderId}/replace-receipt", [
            'multipart' => $multipart,
        ]);

        return new ApiResponseDto($response, OrderDto::class, ErrorDto::class);
    }

    /**
     * Cancel order.
     *
     * @param string $orderId Order UUID
     * @return ApiResponseDto
     */
    public function cancel(string $orderId): ApiResponseDto
    {
        $response = $this->request('PATCH', "orders/{$orderId}/cancel");
        return new ApiResponseDto($response, OrderDto::class, ErrorDto::class);
    }

    /**
     * Set order as finished.
     *
     * @param string $orderId Order UUID
     * @return ApiResponseDto
     */
    public function setFinished(string $orderId): ApiResponseDto
    {
        $response = $this->request('PATCH', "orders/{$orderId}/set-finished");
        return new ApiResponseDto($response, OrderDto::class, ErrorDto::class);
    }

    /**
     * Open dispute for order.
     *
     * @param string $orderId Order UUID
     * @param string|null $filePath Path to receipt file (optional if not uploaded)
     * @param string|null $fileUrl URL of receipt (optional if not uploaded)
     * @return ApiResponseDto
     */
    public function openDispute(string $orderId, ?string $filePath = null, ?string $fileUrl = null): ApiResponseDto
    {
        $multipart = [];
        if ($filePath !== null) {
            if (!file_exists($filePath)) {
                throw new \InvalidArgumentException("File not found: {$filePath}");
            }

            $multipart[] = [
                'name' => 'file',
                'contents' => fopen($filePath, 'r'),
                'filename' => basename($filePath),
            ];
        } elseif ($fileUrl !== null) {
            $multipart[] = [
                'name' => 'file_url',
                'contents' => $fileUrl,
            ];
        }

        $options = [];
        if (!empty($multipart)) {
            $options['multipart'] = $multipart;
        }

        $response = $this->request('POST', "orders/{$orderId}/open-dispute", $options);

        return new ApiResponseDto($response, OrderDto::class, ErrorDto::class);
    }

    /**
     * Get available order statuses.
     *
     * @return array
     */
    public function getAvailableStatuses(): array
    {
        return OrderStatus::getValidValues();
    }
}
