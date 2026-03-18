<?php

namespace Twix\Service;

class ChatService extends AbstractService
{
    /**
     * Get list of chats.
     *
     * @param string|null $status Filter by status (canceled, inprocess, dispute, done)
     * @param bool|null $newMessages Filter by new messages
     * @return array
     */
    public function list(?string $status = null, ?bool $newMessages = null): array
    {
        $query = [];
        if ($status !== null) {
            $query['status'] = $status;
        }
        if ($newMessages !== null) {
            $query['new_messages'] = $newMessages ? 'true' : 'false';
        }

        return $this->request('GET', 'chat/list', ['query' => $query]);
    }

    /**
     * Get chat details.
     *
     * @param int $chatId
     * @return array
     */
    public function get(int $chatId): array
    {
        return $this->request('GET', "chat/{$chatId}");
    }

    /**
     * Send a message to chat.
     *
     * @param int $chatId
     * @param string $message
     * @param string|null $file Base64 encoded file
     * @return array
     */
    public function sendMessage(int $chatId, string $message, ?string $file = null): array
    {
        $formParams = [
            'message' => $message,
        ];
        if ($file !== null) {
            $formParams['file'] = $file;
        }

        return $this->request('POST', "chat/{$chatId}/message/send", [
            'form_params' => $formParams,
        ]);
    }

    /**
     * Get list of messages in chat.
     *
     * @param int $chatId
     * @return array
     */
    public function messages(int $chatId): array
    {
        return $this->request('GET', "chat/{$chatId}/message/list");
    }
}
