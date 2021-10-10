<?php

namespace GitHub;

use Exception;
use WeWork\markdown;

class pullrequest extends github implements GitHubEvent
{
    private const translateList = [
        "opened" => "打开",
        "ready_for_review" => "候审",
        "reopened" => "重新打开",
        "locked" => "锁定",
        "unlocked" => "解锁",
        "closed" => "关闭",
        "assigned" => "分配",
        "unassigned" => "取消分配"
    ];

    function __construct($data = null)
    {
        $this->setEvent("pullrequest");
        parent::__construct($data);
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
        $message->addTitle($message->getLink("#" . $this->data['number'], $this->data['pull_request']['html_url']) . " 有新的 " . $message->getColorText("Pull Request", "info") . " " . $this->_e($this->data['action']));
        $message->addText("仓库: " . $message->getLink($this->data['pull_request']['head']['label'], $this->data['pull_request']['head']['repo']['html_url'] . "/tree/" . $this->data['pull_request']['head']['ref']) . " to " . $message->getLink($this->data['pull_request']['base']['label'], $this->data['pull_request']['base']['repo']['html_url'] . "/tree/" . $this->data['pull_request']['base']['ref']));
        return $message->message();
    }

    private function _e($text): string
    {
        $return = self::translateList[$text];
        if ($text == "closed" && $this->data['merged']) {
            $return = self::translateList[$text] . "并成功合入分支";
        }
        return $return;
    }
}