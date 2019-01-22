<?php

namespace TelegramSwooleClient;

use \danog\MadelineProto;

class Client {

    public $MadelineProto;

    /**
     * Client constructor.
     */
    public function __construct($root)
    {
        $sessionFile = $root . '/session.madeline';

        $config = Config::getInstance()->getConfig('telegram');

        if (empty($config['connection_settings']['all']['proxy_extra']['address'])) {
            unset($config['connection_settings']);
        }

        //При каждой инициализации настройки обновляются из массива $config
        echo PHP_EOL . 'Starting telegram client ...' . PHP_EOL;
        $time = microtime(true);
        $this->MadelineProto = new MadelineProto\API($sessionFile, $config);
        $this->MadelineProto->start();
        $time = round(microtime(true) - $time, 3);
        echo PHP_EOL . "Client started: $time sec" . PHP_EOL;

    }

    /**
     * Получает данные о канале/пользователе по логину
     *
     * @param array $data
     * <pre>
     * $data = [
     *      'id' => , array|string, Например логин в формате '@xtrime'
     * ];
     * </pre>
     *
     * @return array
     */
    public function getInfo($data): array
    {
        $data = array_merge([
            'id' => '',
        ], $data);
        return $this->MadelineProto->get_info($data['id']);
    }


    /**
     * Получает последние сообщения из указанных каналов
     *
     * @param array $data
     * <pre>
     * [
     *     'peer'          => '',
     *     'offset_id'     => 0,
     *     'offset_date'   => 0,
     *     'add_offset'    => 0,
     *     'limit'         => 0,
     *     'max_id'        => 0,
     *     'min_id'        => 0,
     *     'hash'          => 0
     * ]
     * </pre>
     * @return array
     */
    public function getHistory($data): array
    {
        $data = array_merge([
            'peer'          => '',
            'offset_id'     => 0,
            'offset_date'   => 0,
            'add_offset'    => 0,
            'limit'         => 0,
            'max_id'        => 0,
            'min_id'        => 0,
            'hash'          => 0,
        ], $data);

        return $this->MadelineProto->messages->getHistory($data);
    }

    /**
     * Пересылает сообщения
     *
     * @param array $data
     * Id сообщения, или нескольких сообщений
     */
    public function forwardMessages($data): void
    {
        $data = array_merge([
            'from_peer' => '',
            'to_peer'   => '',
            'id'        => [],
        ],$data);
        $this->MadelineProto->messages->forwardMessages($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function searchGlobal(array $data): array
    {
        $data = array_merge([
            'q'             => '',
            'offset_id'     => 0,
            'offset_date'   => 0,
            'limit'         => 10,
        ],$data);
        return $this->MadelineProto->messages->searchGlobal($data);
    }

    /**
     * @param $data
     * @return array
     */
    public function sendMessage($data = []): array
    {
        return $this->MadelineProto->messages->sendMessage(
            array_merge([
                'peer' => '',
                'message' => '',
                'reply_to_msg_id' => 0,
                'parse_mode' => 'HTML',
            ], $data)
        );
    }

}