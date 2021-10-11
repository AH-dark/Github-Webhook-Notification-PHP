<?php

namespace GitHub;

class github
{
    /**
     * @var array Data from body
     */
    protected $data = [];

    /**
     * @var string Event Name
     */
    protected $event = "";

    /**
     * @var string[] GitHub 配置信息
     */
    protected $config = [
        "username" => "",
        "token" => ""
    ];

    function __construct($data)
    {
        if ($data != null) {
            $this->setData($data);
        }
        if(getenv("github.username")) {
            $this->config['username'] = getenv("github.username");
            if(getenv("github.token")) {
                $this->config['token'] = getenv("github.token");
            }
        }
    }

    /**
     * @describe 设置Data
     * @param array $data
     * @return array
     */
    public function setData(array $data): array
    {
        return ($this->data = $data);
    }

    /**
     * @describe 设置Event
     * @param string $event
     * @return string
     */
    public function setEvent(string $event): string
    {
        return ($this->event = $event);
    }

    /**
     * @describe 设置GitHub配置
     * @param string $username
     * @param string $token
     */
    public function setConfig(string $username = "", string $token = "") {
        $this->config['username'] = $username;
        $this->config['token'] = $token;
    }

    protected function isSet(): bool
    {
        return $this->data != [] && $this->event != "";
    }
}