<?php

namespace Twix\Service;

use Twix\Dto\Chat\ChatDto;
use Twix\Dto\Chat\ChatMessageDto;
use Twix\Dto\Chat\ChatMessagesDto;
use Twix\Dto\Global\ApiResponseDto;
use Twix\Dto\Global\ErrorDto;
use Twix\Enum\ChatStatusFilter;

class ChatService extends AbstractService
{
    /**
     * Get list of chats.
     *
     * @param ChatStatus|null $status Filter by status
     * @param bool|null $newMessages Filter by new messages
     * @return ApiResponseDto
     */
    public function list(?ChatStatusFilter $status = null, ?bool $newMessages = null): ApiResponseDto
    {
        $query = [];
        if ($status !== null) {
            $query['status'] = $status->getValue();
        }
        if ($newMessages !== null) {
            $query['new_messages'] = $newMessages ? 1 : 0;
        }

        $response = $this->request('GET', 'chat/list', ['query' => $query]);
        return new ApiResponseDto($response, ChatDto::class, ErrorDto::class);
    }

    /**
     * Get chat details.
     *
     * @param int $chatId
     * @return array
     */
    public function get(int $chatId): ApiResponseDto
    {
        $response = $this->request('GET', "chat/{$chatId}");
        return new ApiResponseDto($response, ChatDto::class, ErrorDto::class);
    }

    /**
     * Send a message to chat.
     *
     * @param int $chatId
     * @param string $message
     * @param string|null $file Base64 encoded file
     * @return ApiResponseDto
     */
    public function sendMessage(int $chatId, string $message, ?string $filePath = null): ApiResponseDto
    {
        $formParams = [
            [
                'name' => 'message',
                'contents' => $message,
            ],
        ];

        if ($filePath !== null) {
            if (!file_exists($filePath)) {
                throw new \InvalidArgumentException("File not found: {$filePath}");
            }

            $formParams[] = [
                'name' => 'file',
                'contents' => fopen($filePath, 'r'),
                'filename' => basename($filePath),
            ];
        }

        $response = $this->request('POST', "chat/{$chatId}/message/send", [
            'multipart' => $formParams,
        ]);

        return new ApiResponseDto($response, ChatDto::class, ErrorDto::class);
    }

    /**
     * Get list of messages in chat.
     *
     * @param int $chatId
     * @return ApiResponseDto
     */
    public function messages(int $chatId): ApiResponseDto
    {
        $response =  $this->request('GET', "chat/{$chatId}/message/list");
            
        return new ApiResponseDto($response, ChatMessagesDto::class, ErrorDto::class);
    }
}
