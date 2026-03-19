<?php

namespace Twix\Service;

use Twix\Dto\Global\ApiResponseDto;
use Twix\Dto\Global\ErrorDto;
use Twix\Dto\Webhook\WebhooksInfoDto;

class WebhookService extends AbstractService
{
    /**
     * Get information about webhooks.
     *
     * @return ApiResponseDto
     */
    public function info(): ApiResponseDto
    {
        $response = $this->request('GET', 'webhooks');
        return new ApiResponseDto($response, WebhooksInfoDto::class, ErrorDto::class);

    }
}
