<?php

namespace Twix\Service;

class PaymentService extends AbstractService
{
    /**
     * Generate payment link.
     *
     * @param string $buttonId Button UUID
     * @param string $signature Signature generated with SignatureHelper
     * @return array|string Returns array if postQuery=true, otherwise redirects (not applicable in SDK, so we return link array)
     */
    public function generatePaymentLink(string $buttonId, string $signature): array
    {
        $data = [
            'button_id' => $buttonId,
            'signature' => $signature,
        ];

        $data['post_query'] = true;

        return $this->request('POST', 'payment/generate_payment_link', [
            'json' => $data,
        ]);
    }

    /**
     * Get list of banks.
     *
     * @return array
     */
    public function banks(): array
    {
        return $this->request('GET', 'payment/banks');
    }
}
