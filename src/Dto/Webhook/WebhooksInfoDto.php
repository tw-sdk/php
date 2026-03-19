<?php

namespace Twix\Dto\Webhook;

/**
 * Class WebhooksInfoDto
 *
 * DTO для информации о всех вебхуках
 */
class WebhooksInfoDto
{
    /**
     * @var WebhookInfoDto[]
     */
    private $webhooks = [];

    /**
     * @var string|null
     */
    private $note;

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();

        $dto->note = $data['note'] ?? null;

        $webhooksData = $data['webhooks'] ?? $data['data']['webhooks'] ?? $data;

        if (is_array($webhooksData)) {
            if (isset($webhooksData[0]) && is_array($webhooksData[0])) {
                $dto->webhooks = WebhookInfoDto::arrayFrom($webhooksData);
            }
        }

        return $dto;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [
            'webhooks' => array_map(function(WebhookInfoDto $webhook) {
                return $webhook->toArray();
            }, $this->webhooks),
        ];

        if ($this->note !== null) {
            $result['note'] = $this->note;
        }

        return $result;
    }

    /**
     * Get all webhooks.
     *
     * @return WebhookInfoDto[]
     */
    public function getWebhooks(): array
    {
        return $this->webhooks;
    }

    /**
     * Get note.
     *
     * @return string|null
     */
    public function getNote(): ?string
    {
        return $this->note;
    }
}
