<?php

namespace Twix\Dto\Chat;

use Twix\Dto\Chat\MessageDto;

/**
 * Class ChatMessagesDto
 *
 * DTO для сообщений чата, сгруппированных по датам
 */
class ChatMessagesDto
{
    /**
     * @var array<string, MessageDto[]>
     */
    private $messagesByDate = [];

    /**
     * @var MessageDto[]
     */
    private $allMessages = [];

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();

        foreach ($data as $date => $messages) {
            if (is_string($date) && is_array($messages)) {
                $dateMessages = [];
                foreach ($messages as $messageData) {
                    if (is_array($messageData)) {
                        $dateMessages[] = MessageDto::fromArray($messageData);
                    }
                }

                if (!empty($dateMessages)) {
                    $dto->messagesByDate[$date] = $dateMessages;
                    $dto->allMessages = array_merge($dto->allMessages, $dateMessages);
                }
            }
        }

        return $dto;
    }

    /**
     * Get messages grouped by date.
     *
     * @return array<string, MessageDto[]>
     */
    public function getMessagesByDate(): array
    {
        return $this->messagesByDate;
    }

    /**
     * Get all messages flat list.
     *
     * @return MessageDto[]
     */
    public function getAllMessages(): array
    {
        return $this->allMessages;
    }

    /**
     * Get messages for specific date.
     *
     * @param string $date
     * @return MessageDto[]
     */
    public function getByDate(string $date): array
    {
        return $this->messagesByDate[$date] ?? [];
    }

    /**
     * Get available dates.
     *
     * @return string[]
     */
    public function getDates(): array
    {
        return array_keys($this->messagesByDate);
    }

    /**
     * Get messages count.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->allMessages);
    }

    /**
     * Convert to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this->messagesByDate as $date => $messages) {
            $result[$date] = array_map(function(MessageDto $message) {
                return $message->toArray();
            }, $messages);
        }

        return $result;
    }
}
