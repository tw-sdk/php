<?php

namespace Twix\Service;

use Twix\Dto\Global\ApiResponseDto;
use Twix\Dto\Global\BankDto;
use Twix\Dto\Global\ErrorDto;
use Twix\Dto\Wallet\BalanceDto;
use Twix\Dto\Wallet\DepositAddressDto;

class WalletService extends AbstractService
{
    /**
     * Get user balances.
     *
     * @return ApiResponseDto
     */
    public function balances(): ApiResponseDto
    {
        $response = $this->request('GET', 'wallet/balances');

        $balances = [];

        if (!empty($response['data']['balances'])) {
            foreach ($response['data']['balances'] as $bankData) {
                if (!empty($bankData['currency'])) {
                    $balances[] = BalanceDto::fromArray($bankData);
                }
            }
        }

        $balances['code'] = $response['code'] ?? null;

        return new ApiResponseDto($balances, null, ErrorDto::class);
    }

    /**
     * Get deposit addresses.
     *
     * @return ApiResponseDto
     */
    public function depositAddresses(): ApiResponseDto
    {
        $response = $this->request('GET', 'wallet/deposit-addresses');
        $wallets = [];

        if (!empty($response['data']['deposit_addresses'])) {
            foreach ($response['data']['deposit_addresses'] as $walletData) {
                if (!empty($walletData['currency'])) {
                    $wallets[] = DepositAddressDto::fromArray($walletData);
                }
            }
        }

        $wallets['code'] = $response['code'] ?? null;
        return new ApiResponseDto($wallets, null, ErrorDto::class);
    }
}
