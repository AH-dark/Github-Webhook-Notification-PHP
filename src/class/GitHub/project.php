<?php

namespace GitHub;

use Exception;
use WeWork\markdown;

class project extends github implements GitHubEvent
{

    public function __construct($data = null)
    {
        $this->setEvent("project");
        parent::__construct($data);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getMessage(): string
    {
        if (!$this->isSet()) {
            throw new Exception("未设置data或event");
        }

        $data = $this->data;
        $action = $data['action'];
        $projectSubData = $data['project'];
        $message = new markdown();

        switch ($action) {
            case "created":
                $message->addTitle("有新的项目创建");
                $message->addText("项目名：" . $projectSubData['name']);
                $message->addText("描述：" . $projectSubData['body']);
                $message->addText("项目号：" . $projectSubData['number']);
                $message->addLine();
                $message->addText("创建者：" . $message->getLink($projectSubData['creator']['login'], $projectSubData['creator']['html_url']));
                if (!empty($data['repository'])) {
                    $message->addText("该项目从属于仓库 " . $message->getLink($data['repository']['full_name'], $data['repository']['url']));
                } else if (!empty($data['organization'])) {
                    $message->addText("该项目从属于组织 " . $message->getLink($data['organization']['login'], "https://GitHub.com/" . $data['organization']['login']));
                }
                $message->addLine();
                $message->addText($message->getLink("点击查看项目", $projectSubData['html_url']));
                break;
            case "closed":
                $message->addTitle("项目 " . $message->getLink($projectSubData['name'], $projectSubData['html_url']) . " 已关闭");
                $message->addText("项目号：" . $projectSubData['number']);
                $message->addLine();
                $message->addText("操作者：" . $message->getLink($data['sender']['login'], $data['sender']['html_url']));
                if (!empty($data['repository'])) {
                    $message->addText("该项目从属于仓库 " . $message->getLink($data['repository']['full_name'], $data['repository']['html_url']));
                } else if (!empty($data['organization'])) {
                    $message->addText("该项目从属于组织 " . $message->getLink($data['organization']['login'], "https://GitHub.com/" . $data['organization']['login']));
                }
                $message->addLine();
                $message->addText($message->getLink("点击查看项目", $projectSubData['html_url']));
                break;
            case "reopened":
                $message->addTitle("项目 " . $message->getLink($projectSubData['name'], $projectSubData['html_url']) . " 被重新开启");
                $message->addText("项目号：" . $projectSubData['number']);
                $message->addLine();
                $message->addText("操作者：" . $message->getLink($data['sender']['login'], $data['sender']['html_url']));
                if (!empty($data['repository'])) {
                    $message->addText("该项目从属于仓库 " . $message->getLink($data['repository']['full_name'], $data['repository']['html_url']));
                } else if (!empty($data['organization'])) {
                    $message->addText("该项目从属于组织 " . $message->getLink($data['organization']['login'], "https://GitHub.com/" . $data['organization']['login']));
                }
                $message->addLine();
                $message->addText($message->getLink("点击查看项目", $projectSubData['html_url']));
                break;
            case "deleted":
                $message->addTitle("项目 " . $message->getLink($projectSubData['name'], $projectSubData['html_url']) . " 被删除");
                $message->addText("项目序号：" . $projectSubData['number']);
                $message->addLine();
                $message->addText("操作者：" . $message->getLink($data['sender']['login'], $data['sender']['html_url']));
                if (!empty($data['repository'])) {
                    $message->addText("该项目从属于仓库 " . $message->getLink($data['repository']['full_name'], $data['repository']['html_url']));
                } else if (!empty($data['organization'])) {
                    $message->addText("该项目从属于组织 " . $message->getLink($data['organization']['login'], "https://GitHub.com/" . $data['organization']['login']));
                }
                break;
            default:
                die("Action尚未支持");
        }
        return $message->message();
    }
}