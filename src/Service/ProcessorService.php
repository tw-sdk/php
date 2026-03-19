<?php

namespace Twix\Service;

use Twix\Dto\Global\ApiResponseDto;
use Twix\Dto\Global\ErrorDto;
use Twix\Dto\Global\OrderDto;
use Twix\Dto\Processor\PoolDto;
use Twix\Dto\Processor\TakeOrderDto;
use Twix\Enum\OrderStatus;
use Twix\Enum\OrderStatusFilter;

class ProcessorService extends AbstractService
{
    /**
     * Get list of orders for processor.
     *
     * @param string|null $status Filter by status (active, dispute, finished, canceled, canceled_confirmed)
     * @param int|null $start Start position
     * @param int|null $limit Limit (max 100)
     * @return array
     */
    public function orders(?OrderStatusFilter $status = null, ?int $start = null, ?int $limit = null): ApiResponseDto
    {
        $query = [];
        if ($status !== null) {
            $query['status'] = $status->getValue();
        }
        if ($start !== null) {
            $query['start'] = $start;
        }
        if ($limit !== null) {
            $query['limit'] = $limit;
        }

        $response = $this->request('GET', 'processor/orders', ['query' => $query]);
        return new ApiResponseDto($response, null, ErrorDto::class);
    }

    /**
     * Get JSON getPool of available orders.
     *
     * @return array
     */
    public function getPool(): ApiResponseDto
    {
        $response = $this->request('GET', 'processor/orders.json');

        if (!empty($response['orders'])) {
            foreach ($response['orders'] as $orderData) {
                if (!empty($orderData['id'])) {
                    $orders[] = PoolDto::fromArray($orderData);
                }
            }
        }
        $orders['code'] = $response['code'] ?? null;
        $orders['count'] = $response['count'] ?? null;
        return new ApiResponseDto($orders, null, ErrorDto::class);
    }

    /**
     * Take an order from pool.
     *
     * @param TakeOrderDto $data
     * @return ApiResponseDto
     */
    public function takeOrder(TakeOrderDto $data): ApiResponseDto
    {
        $response = $this->request('POST', 'processor/orders/take', [
            'json' => $data->toArray(),
        ]);

        return new ApiResponseDto($response, OrderDto::class, ErrorDto::class);
    }

    /**
     * Request documents for order and open support chat.
     *
     * @param string $orderId Order UUID
     * @return array
     */
    public function requestDocuments(string $orderId): ApiResponseDto
    {
        $response = $this->request('POST', 'processor/orders/document', [
            'query' => ['order_id' => $orderId],
        ]);
        return new ApiResponseDto($response, OrderDto::class, ErrorDto::class);
    }

    /**
     * Mark order as paid (processor).
     *
     * @param string $orderId Order UUID
     * @return array
     */
    public function markPaid(string $orderId): ApiResponseDto
    {
        $response = $this->request('PATCH', "orders/{$orderId}/mark-paid");
        return new ApiResponseDto($response, OrderDto::class, ErrorDto::class);
    }
}
