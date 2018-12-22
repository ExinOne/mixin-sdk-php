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
        $this->wsClient      = new Client("wss://blaze.mixin.one/", 'https://google.com');
        $this->wsClient->addRequestHeader('Authorization', 'Bearer '.$this->getToken('GET', '/', ""));
        $this->wsClient->addRequestHeader('protocol', 'Mixin-Blaze-1');
    }

    /**
     * @param        $user_id
     * @param        $data
     * @param string $category
     * @param null   $conversation_id
     *
     * @return array
     * @throws \Exception
     */
    public function sendText($user_id, $data, $category = 'CONTACT', $conversation_id = null)
    {
        $message = [
            'id'     => Uuid::uuid4()->toString(),
            'action' => 'CREATE_MESSAGE',
            'params' => [
                'conversation_id' => $category == 'CONTACT'
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
    //public function sendImage()
    //{
    //
    //}

    // TODO
    //public function sendData()
    //{
    //
    //}

    // TODO
    //public function sendSticker()
    //{
    //
    //}

    public function sendContact($user_id, $contact_id, $category = 'CONTACT', $conversation_id = null)
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

    public function sendAppButtonGroup()
    {

    }

    public function sendAppCard()
    {

    }

    public function sendBatchMessage(array $user_ids, $data)
    {
        if (! is_string($data) && (count($user_ids) != count($data))) {
            throw new InternalErrorException('The length of "user_ids" and "data" is not equal');
        }

        if (count($user_ids) > 100) {

        }

        $message = [
            'id'     => Uuid::uuid4()->toString(),
            'action' => 'CREATE_PLAIN_MESSAGES',
            'params' => [
                'messages' => [
                ],
            ],
        ];

        foreach ($user_ids as $k => $v) {
            $message['params']['messages'][] = [
                'conversation_id' => $this->uniqueConversationId($v, $this->config['client_id']),
                'recipient_id'    => $v,
                'message_id'      => Uuid::uuid4()->toString(),
                'category'        => 'PLAIN_TEXT',
                'data'            => base64_encode(is_string($data) ? $data : $data[$k] ?? 'default'),
            ];
        }

        return $this->webSocketRes($message);
    }
}
