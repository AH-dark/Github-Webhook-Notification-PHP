<?php

namespace GitHub;

use Exception;
use WeWork\markdown;

class issues extends github
{
    function __construct($data = null)
    {
        $this->setEvent("push");
        if ($data != null) {
            $this->setData($data);
        }
    }

    /**
     * @return string
     * @throws Exception
     * @noinspection HtmlDeprecatedTag
     */
    public function getMessage(): string
    {
        if (!$this->isSet()) {
            throw new Exception("未设置data或event");
        }

        $message = new markdown();
        $message->addTitle("Issue: " . $message->getLink("#" . $this->data['issue']['number'] . " " . $this->data['issue']['title'], $this->data['issue']['html_url']) . " " . $this->_e($this->data['action']));
        $message->addText("发起者: " . $message->getLink($this->data['issue']['assignee']['login'], $this->data['issue']['assignee']['html_url']));
        $message->addText("仓库: " . $message->getLink($this->data['repository']['name'], $this->data['repository']['html_url']));
        if (isset($this->data['issue']['labels'])) {
            $labelList = [];
            foreach ($this->data['issue']['labels'] as $label) {
                $labelList[] .= $label['name'];
            }
            $message->addList($labelList);
        }
        return $message->message();
    }

    function _e($text): string
    {
        $translate = [
            "opened" => "被打开",
            "deleted" => "被删除",
            "closed" => "被关闭",
            "reopened" => "被重新打开",
            "labeled" => "被标记",
            "unlabeled" => "被取消标记",
            "edited" => "被修改"
        ];
        return $translate[$text];
    }
}