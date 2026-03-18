<?php

namespace Twix\Service;

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
    public function orders(?string $status = null, ?int $start = null, ?int $limit = null): array
    {
        $query = [];
        if ($status !== null) {
            $query['status'] = $status;
        }
        if ($start !== null) {
            $query['start'] = $start;
        }
        if ($limit !== null) {
            $query['limit'] = $limit;
        }

        return $this->request('GET', 'processor/orders', ['query' => $query]);
    }

    /**
     * Get JSON feed of available orders.
     *
     * @return array
     */
    public function feed(): array
    {
        return $this->request('GET', 'processor/orders.json');
    }

    /**
     * Take an order from pool.
     *
     * @param string $orderId Order UUID
     * @param string|integer $paymentDetails Payment details (card number, phone, qr_ncpk)
     * @param string $paymentSystem Payment system (ccard, sbp, sim, qr_ncpk)
     * @param string $paymentBank Bank name
     * @param string|null $paymentFio Client full name (optional)
     * @param string|null $mobileOperator Mobile operator (required for sim)
     * @return array
     */
    public function takeOrder(
        string $orderId,
        string $paymentDetails,
        string $paymentSystem,
        ?string $paymentBank,
        ?string $paymentFio = null,
        ?string $mobileOperator = null
    ): array {
        $data = [
            'order_id' => $orderId,
            'payment_details' => $paymentDetails,
            'payment_system' => $paymentSystem,
            'payment_bank' => $paymentBank,
        ];
        if ($paymentFio !== null) {
            $data['payment_fio'] = $paymentFio;
        }
        if ($mobileOperator !== null) {
            $data['mobile_operator'] = $mobileOperator;
        }

        return $this->request('POST', 'processor/orders/take', [
            'json' => $data,
        ]);
    }

    /**
     * Request documents for order and open support chat.
     *
     * @param string $orderId Order UUID
     * @return array
     */
    public function requestDocuments(string $orderId): array
    {
        return $this->request('POST', 'processor/orders/document', [
            'query' => ['order_id' => $orderId],
        ]);
    }

    /**
     * Mark order as paid (processor).
     *
     * @param string $orderId Order UUID
     * @return array
     */
    public function markPaid(string $orderId): array
    {
        return $this->request('PATCH', "orders/{$orderId}/mark-paid");
    }
}
