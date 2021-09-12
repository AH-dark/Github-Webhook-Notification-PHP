<?php

namespace WeWork;

use Exception;

class GroupBot
{

    /**
     * 企业微信群机器人发送秘钥
     * @const
     */
    private $sendKey = "";

    /**
     * @describe 设置SendKey
     * @param string SendKey
     */
    public function setSendKey(string $key): string
    {
        return ($this->sendKey = $key);
    }

    /**
     * 发送文本消息
     * @param string $message 文本消息
     * @param array $mentioned_list At列表
     */
    public function sendMessage(string $message, array $mentioned_list = []): array
    {
        $data = json_encode([
            "msgtype" => "text",
            "text" => [
                "content" => $message,
                "mentioned_list" => $mentioned_list
            ]
        ]);
        return $this->curl_send($data);
    }

    /**
     * @describe 发送请求
     * @param string $data
     * @param array $header
     * @return array
     */
    protected function curl_send(string $data, array $header = ["Content-type:application/json;charset='utf-8'", "Accept:application/json"]): array
    {
        $ch = curl_init();
        try {
            curl_setopt_array($ch, [
                CURLOPT_URL => $this->getSendUrl(),
                CURLOPT_POST => 1,
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_POSTFIELDS => $data
            ]);
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
        $res = curl_exec($ch);
        return json_decode($res, 1);
    }

    /**
     * 获取Gateway Url
     * @return string 企业微信发送地址
     * @throws Exception
     * @api
     */
    protected function getSendUrl(): string
    {
        if ($this->sendKey == "") {
            throw new Exception("send key unset");
        }
        return "https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=" . $this->sendKey;
    }

    /**
     * @describe 发送Markdown消息
     * @param string $message
     * @return array
     */
    public function sendMarkdownMessage(string $message): array
    {
        $data = json_encode([
            "msgtype" => "markdown",
            "markdown" => [
                "content" => $message
            ]
        ]);
        return $this->curl_send($data);
    }

}