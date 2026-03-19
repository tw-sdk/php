<?php

namespace Twix\Service;

use Twix\Dto\Global\ApiResponseDto;
use Twix\Dto\Global\BankDto;
use Twix\Dto\Global\ErrorDto;
use Twix\Dto\Processor\PoolDto;

class PaymentService extends AbstractService
{

    /**
     * Generate payment link.
     *
     * @param string $buttonId Button UUID
     * @param string $signature Signature generated with SignatureHelper
     * @return ApiResponseDto
     */
    public function generatePaymentLink(string $buttonId, string $signature): ApiResponseDto
    {
        $data = [
            'button_id' => $buttonId,
            'signature' => $signature,
        ];

        $data['post_query'] = true;

        $response = $this->request('POST', 'payment/generate_payment_link', [
            'json' => $data,
        ]);

        return new ApiResponseDto($response, null, ErrorDto::class);
    }

    /**
     * Get list of banks.
     *
     * @return ApiResponseDto
     */
    public function banks(): ApiResponseDto
    {
        $response =  $this->request('GET', 'payment/banks');
        $banks = [];
        if (!empty($response['banks'])) {
            foreach ($response['banks'] as $bankData) {
                if (!empty($bankData['id'])) {
                    $banks[] = BankDto::fromArray($bankData);
                }
            }
        }
        $banks['code'] = $response['code'] ?? null;
        $banks['count'] = $response['count'] ?? null;
        return new ApiResponseDto($banks, null, ErrorDto::class);
    }
}
