<?php

namespace Twix\Service;

class WalletService extends AbstractService
{
    /**
     * Get user balances.
     *
     * @return array
     */
    public function balances(): array
    {
        return $this->request('GET', 'wallet/balances');
    }

    /**
     * Get deposit addresses.
     *
     * @return array
     */
    public function depositAddresses(): array
    {
        return $this->request('GET', 'wallet/deposit-addresses');
    }
}