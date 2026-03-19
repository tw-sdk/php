<?php

namespace Twix\Service;

use Twix\Dto\Global\ApiResponseDto;
use Twix\Dto\Global\ErrorDto;
use Twix\Dto\Global\OrderDto;
use Twix\Dto\Global\TickerDto;

class TickerService extends AbstractService
{
    public function getRates(): ApiResponseDto
    {
        $response = $this->request('GET', 'ticker');
        return new ApiResponseDto($response, TickerDto::class, ErrorDto::class);
    }
}
