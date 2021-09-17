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

    protected function isSet(): bool
    {
        return $this->data != [] && $this->event != "";
    }
}