<?php

namespace Twix\Service;

class OrderService extends AbstractService
{
    /**
     * Get list of orders.
     *
     * @param string|null $status Filter by status (requisite_added, marked_paid)
     * @param int|null $perPage Items per page (max 100)
     * @return array
     */
    public function list(?string $status = null, ?int $perPage = null): array
    {
        $query = [];
        if ($status !== null) {
            $query['status'] = $status;
        }
        if ($perPage !== null) {
            $query['per_page'] = $perPage;
        }

        return $this->request('GET', 'orders', ['query' => $query]);
    }

    /**
     * Get order details.
     *
     * @param string $orderId Order UUID
     * @return array
     */
    public function get(string $orderId): array
    {
        return $this->request('GET', "orders/{$orderId}");
    }

    /**
     * Create a new order.
     *
     * @param array $data Order data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->request('POST', 'orders', [
            'json' => $data,
        ]);
    }

    /**
     * Upload receipt for order.
     *
     * @param string $orderId Order UUID
     * @param string|null $filePath Path to file (multipart)
     * @param string|null $fileUrl URL of receipt
     * @return array
     */
    public function uploadReceipt(string $orderId, ?string $filePath = null, ?string $fileUrl = null): array
    {
        if ($filePath === null && $fileUrl === null) {
            throw new \InvalidArgumentException('Either filePath or fileUrl must be provided');
        }

        $multipart = [];
        if ($filePath !== null) {
            $multipart[] = [
                'name' => 'receipt',
                'contents' => fopen($filePath, 'r'),
            ];
        } else {
            $multipart[] = [
                'name' => 'file_url',
                'contents' => $fileUrl,
            ];
        }

        return $this->request('POST', "orders/{$orderId}/receipt", [
            'multipart' => $multipart,
        ]);
    }

    /**
     * Replace receipt for order (in dispute/canceled status).
     *
     * @param string $orderId Order UUID
     * @param string|null $filePath Path to file
     * @param string|null $fileUrl URL of receipt
     * @return array
     */
    public function replaceReceipt(string $orderId, ?string $filePath = null, ?string $fileUrl = null): array
    {
        if ($filePath === null && $fileUrl === null) {
            throw new \InvalidArgumentException('Either filePath or fileUrl must be provided');
        }

        $multipart = [];
        if ($filePath !== null) {
            $multipart[] = [
                'name' => 'file',
                'contents' => fopen($filePath, 'r'),
            ];
        } else {
            $multipart[] = [
                'name' => 'file_url',
                'contents' => $fileUrl,
            ];
        }

        return $this->request('POST', "orders/{$orderId}/replace-receipt", [
            'multipart' => $multipart,
        ]);
    }

    /**
     * Cancel order.
     *
     * @param string $orderId Order UUID
     * @return array
     */
    public function cancel(string $orderId): array
    {
        return $this->request('PATCH', "orders/{$orderId}/cancel");
    }

    /**
     * Set order as finished.
     *
     * @param string $orderId Order UUID
     * @return array
     */
    public function setFinished(string $orderId): array
    {
        return $this->request('PATCH', "orders/{$orderId}/set-finished");
    }

    /**
     * Open dispute for order.
     *
     * @param string $orderId Order UUID
     * @param string|null $filePath Path to receipt file (optional if not uploaded)
     * @param string|null $fileUrl URL of receipt (optional if not uploaded)
     * @return array
     */
    public function openDispute(string $orderId, ?string $filePath = null, ?string $fileUrl = null): array
    {
        $multipart = [];
        if ($filePath !== null) {
            $multipart[] = [
                'name' => 'file',
                'contents' => fopen($filePath, 'r'),
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

        return $this->request('POST', "orders/{$orderId}/open-dispute", $options);
    }
}