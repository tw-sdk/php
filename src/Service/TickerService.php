<?php

namespace Twix\Service;

class TickerService extends AbstractService
{
    public function getRates(): array
    {
        return $this->request('GET', 'ticker');
    }
}