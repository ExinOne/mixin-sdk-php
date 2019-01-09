<?php
/**
 * Created by PhpStorm.
 * User: kurisu
 * Date: 18-11-12
 * Time: 上午11:52
 */

namespace ExinOne\MixinSDK\Apis;

use ExinOne\MixinSDK\Exceptions\InternalErrorException;
use Ramsey\Uuid\Uuid;
use Wrench\Client;

class Message extends Api
{
    /**
     * Message constructor.
     *
     * @param $config
     *
     * @throws \Exception
     */
    public function __construct($config)
    {
        $this->packageConfig = require(__DIR__.'/../../config/config.php');
        $this->config        = $config;
        $this->wsClient      = new Client('wss://blaze.mixin.one/', 'https://google.com');
        $this->wsClient->addRequestHeader('Authorization', 'Bearer '.$this->getToken('GET', '/', ''));
        $this->wsClient->addRequestHeader('protocol', 'Mixin-Blaze-1');
    }

    /**
     * @param string      $user_id
     * @param string      $data
     * @param string      $category
     * @param string|null $conversation_id
     *
     * @return array
     * @throws \Wrench\Exception\FrameException
     * @throws \Wrench\Exception\SocketException
     */
    public function sendText(string $user_id, string $data, string $category = 'CONTACT', string $conversation_id = null): array
    {
        $message = [
            'id'     => Uuid::uuid4()->toString(),
            'action' => 'CREATE_MESSAGE',
            'params' => [
                'conversation_id' => $category == 'CONTACT' && empty($conversation_id)
                    ? $this->uniqueConversationId($user_id, $this->config['client_id'])
                    : $conversation_id,
                'message_id'      => Uuid::uuid4()->toString(),
                'category'        => 'PLAIN_TEXT',
                'data'            => base64_encode($data),
            ],
        ];

        return $this->webSocketRes($message);
    }

    // TODO
    //public function sendImage(): array
    //{
    //
    //}

    // TODO
    //public function sendData(): array
    //{
    //
    //}

    // TODO
    //public function sendSticker(): array
    //{
    //
    //}

    /**
     * @param string $user_id
     * @param string $contact_id
     * @param string $category
     * @param null   $conversation_id
     *
     * @return array
     * @throws \Wrench\Exception\FrameException
     * @throws \Wrench\Exception\SocketException
     */
    public function sendContact(string $user_id, string $contact_id, string $category = 'CONTACT', $conversation_id = null): array
    {
        $message = [
            'id'     => Uuid::uuid4()->toString(),
            'action' => 'CREATE_MESSAGE',
            'params' => [
                'conversation_id' => $category == 'CONTACT'
                    ? $this->uniqueConversationId($user_id, $this->config['client_id'])
                    : $conversation_id,
                'message_id'      => Uuid::uuid4()->toString(),
                'category'        => 'PLAIN_CONTACT',
                'data'            => base64_encode(json_encode([
                    'user_id' => $contact_id,
                ])),
            ],
        ];

        return $this->webSocketRes($message);
    }

    /**
     * @param string $user_id
     * @param array  $data
     * @param string $category
     * @param null   $conversation_id
     *
     * @return array
     * @throws \Wrench\Exception\FrameException
     * @throws \Wrench\Exception\SocketException
     */
    public function sendAppButtonGroup(string $user_id, array $data, string $category = 'CONTACT', $conversation_id = null): array
    {
        $message = [
            'id'     => Uuid::uuid4()->toString(),
            'action' => 'CREATE_MESSAGE',
            'params' => [
                'conversation_id' => $category == 'CONTACT'
                    ? $this->uniqueConversationId($user_id, $this->config['client_id'])
                    : $conversation_id,
                'message_id'      => Uuid::uuid4()->toString(),
                'category'        => 'APP_BUTTON_GROUP',
                'data'            => base64_encode(json_encode($data)),
            ],
        ];

        return $this->webSocketRes($message);
    }

    /**
     * @param string $user_id
     * @param array  $data
     * @param string $category
     * @param null   $conversation_id
     *
     * @return array
     * @throws \Wrench\Exception\FrameException
     * @throws \Wrench\Exception\SocketException
     */
    public function sendAppCard(string $user_id, array $data, string $category = 'CONTACT', $conversation_id = null): array
    {
        $message = [
            'id'     => Uuid::uuid4()->toString(),
            'action' => 'CREATE_MESSAGE',
            'params' => [
                'conversation_id' => $category == 'CONTACT'
                    ? $this->uniqueConversationId($user_id, $this->config['client_id'])
                    : $conversation_id,
                'message_id'      => Uuid::uuid4()->toString(),
                'category'        => 'APP_CARD',
                'data'            => base64_encode(json_encode($data)),
            ],
        ];

        return $this->webSocketRes($message);
    }

    /**
     * @param $message_id
     *
     * @return array
     * @throws \Wrench\Exception\FrameException
     * @throws \Wrench\Exception\SocketException
     */
    public function askMessageReceipt(string $message_id): array
    {
        $message = [
            'id'     => Uuid::uuid4()->toString(),
            'action' => 'ACKNOWLEDGE_MESSAGE_RECEIPT',
            'params' => [
                'message_id' => $message_id,
                'status'     => 'READ',
            ],
        ];

        return $this->webSocketRes($message);
    }

    /**
     * @param array $user_ids
     * @param       $data
     *
     * @return array
     * @throws InternalErrorException
     * @throws \Wrench\Exception\FrameException
     * @throws \Wrench\Exception\SocketException
     */
    public function sendBatchMessage(array $user_ids, $data): array
    {
        // 如果 count 不相等的话
        if (! is_string($data) && (count($user_ids) != count($data))) {
            throw new InternalErrorException('The length of "user_ids" and "data" is not equal');
        }

        $messages        = [];
        $messageTemplate = [
            'id'     => Uuid::uuid4()->toString(),
            'action' => 'CREATE_PLAIN_MESSAGES',
            'params' => [
                'messages' => [
                ],
            ],
        ];
        $message         = $messageTemplate;

        foreach ($user_ids as $k => $v) {
            $message['params']['messages'][] = [
                'conversation_id' => $this->uniqueConversationId($v, $this->config['client_id']),
                'recipient_id'    => $v,
                'message_id'      => Uuid::uuid4()->toString(),
                'category'        => 'PLAIN_TEXT',
                'data'            => base64_encode(is_string($data) ? $data : $data[$k] ?? 'default'),
            ];

            if (count($message['params']['messages']) == 100 || $k == count($user_ids) - 1) {
                $messages[] = $message;
                $message    = $messageTemplate;
            }
        }

        return $this->webSocketRes($messages);
    }
}
