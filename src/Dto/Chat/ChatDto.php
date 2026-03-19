<?php

namespace Twix\Dto\Chat;

use Twix\Dto\Global\OrderDto;

/**
 * Class ChatDto
 *
 * DTO для чата
 */
class ChatDto
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $newMessage;

    /**
     * @var string|null
     */
    private $orderStatus;

    /**
     * @var bool
     */
    private $close;

    /**
     * @var OrderDto|null
     */
    private $order;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var MessageDto[]|null
     */
    private $messages;

    /**
     * @var bool|null
     */
    private $muted;

    /**
     * @var string|null
     */
    private $status;

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();

        $dto->id = (int) ($data['id'] ?? 0);
        $dto->newMessage = (int) ($data['new_message'] ?? 0);
        $dto->orderStatus = $data['order_status'] ?? null;
        $dto->close = (bool) ($data['close'] ?? false);
        $dto->createdAt = $data['created_at'] ?? '';
        $dto->muted = $data['muted'] ?? null;
        $dto->status = $data['status'] ?? null;

        if (isset($data['order']) && is_array($data['order'])) {
            $dto->order = OrderDto::fromArray($data['order']);
        }

        if (isset($data['messages']) && is_array($data['messages'])) {
            $dto->messages = MessageDto::arrayFrom($data['messages']);
        }

        return $dto;
    }

    /**
     * @param array $items
     * @return self[]
     */
    public static function arrayFrom(array $items): array
    {
        $result = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                $result[] = self::fromArray($item);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'new_message' => $this->newMessage,
            'order_status' => $this->orderStatus,
            'close' => $this->close,
            'created_at' => $this->createdAt,
        ];

        if ($this->order !== null) {
            $data['order'] = $this->order->toArray();
        }

        if ($this->messages !== null) {
            $data['messages'] = array_map(function(MessageDto $msg) {
                return $msg->toArray();
            }, $this->messages);
        }

        if ($this->muted !== null) {
            $data['muted'] = $this->muted;
        }

        if ($this->status !== null) {
            $data['status'] = $this->status;
        }

        return $data;
    }

    public function getId(): int { return $this->id; }
    public function getNewMessage(): int { return $this->newMessage; }
    public function getOrderStatus(): ?string { return $this->orderStatus; }
    public function isClose(): bool { return $this->close; }
    public function getOrder(): ?OrderDto { return $this->order; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function getMessages(): ?array { return $this->messages; }
    public function isMuted(): ?bool { return $this->muted; }
    public function getStatus(): ?string { return $this->status; }
}
