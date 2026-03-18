<?php

namespace Twix\Service;

class WebhookService extends AbstractService
{
    /**
     * Get information about webhooks.
     *
     * @return array
     */
    public function info(): array
    {
        return $this->request('GET', 'webhooks');
    }
}