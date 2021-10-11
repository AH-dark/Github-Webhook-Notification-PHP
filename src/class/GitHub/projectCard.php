<?php

namespace GitHub;

use Exception;
use WeWork\markdown;

class projectCard extends github implements GitHubEvent
{

    public function __construct($data)
    {
        $this->setEvent("project_card");
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
        $project_card = $data['project_card'];
        $m = new markdown();

        switch ($data['action']) {
            case "created":
                $m->addTitle("有新的项目卡片创建");
                $m->addText("内容：");
                $m->addQuote($project_card['note']);
                $m->addLine();
                $m->addText("创建者：" . $m->getLink($project_card['creator']['login'], $project_card['creator']['html_url']));
                if (!empty($data['repository'])) {
                    $m->addText("项目从属于仓库 " . $m->getLink($data['repository']['full_name'], $data['repository']['html_url']));
                } else if (!empty($data['organization'])) {
                    $m->addText("项目从属于组织 " . $m->getLink($data['organization']['login'], "https://GitHub.com/" . $data['organization']['login']));
                }
                break;
            case "deleted":
                $m->addTitle("项目卡片被删除");
                $m->addText("内容：");
                $m->addQuote($project_card['note']);
                $m->addLine();
                $m->addText("操作者：" . $m->getLink($data['sender']['login'], $data['sender']['html_url']));
                if (!empty($data['repository'])) {
                    $m->addText("项目从属于仓库 " . $m->getLink($data['repository']['full_name'], $data['repository']['html_url']));
                } else if (!empty($data['organization'])) {
                    $m->addText("项目从属于组织 " . $m->getLink($data['organization']['login'], "https://GitHub.com/" . $data['organization']['login']));
                }
                break;
            case "converted":
                $m->addTitle("项目卡片被转换");
                $m->addText("原有信息：");
                $m->addText($data['changes']['note']['from']);
                $m->addLine();
                $m->addText("内容：");
                $m->addQuote($project_card['note']);
                $m->addLine();
                $m->addText("操作者：" . $m->getLink($data['sender']['login'], $data['sender']['html_url']));
                if (!empty($data['repository'])) {
                    $m->addText("项目从属于仓库 " . $m->getLink($data['repository']['full_name'], $data['repository']['html_url']));
                } else if (!empty($data['organization'])) {
                    $m->addText("项目从属于组织 " . $m->getLink($data['organization']['login'], "https://GitHub.com/" . $data['organization']['login']));
                }
                break;
            case "edited":
                $m->addTitle("项目卡片内容更变");
                $m->addText("原有信息：");
                $m->addText($data['changes']['note']['from']);
                $m->addLine();
                $m->addText("内容：");
                $m->addQuote($project_card['note']);
                $m->addLine();
                $m->addText("操作者：" . $m->getLink($data['sender']['login'], $data['sender']['html_url']));
                if (!empty($data['repository'])) {
                    $m->addText("项目从属于仓库 " . $m->getLink($data['repository']['full_name'], $data['repository']['html_url']));
                } else if (!empty($data['organization'])) {
                    $m->addText("项目从属于组织 " . $m->getLink($data['organization']['login'], "https://GitHub.com/" . $data['organization']['login']));
                }
                break;
            case "moved":
                $m->addTitle("项目卡片被移动");
                $m->addText("原列：".$data['changes']['column_id']['from']);
                $m->addText("现列：".$project_card['column_id']);
                $m->addText("内容：");
                $m->addQuote($project_card['note']);
                $m->addLine();
                $m->addText("操作者：" . $m->getLink($data['sender']['login'], $data['sender']['html_url']));
                if (!empty($data['repository'])) {
                    $m->addText("项目从属于仓库 " . $m->getLink($data['repository']['full_name'], $data['repository']['html_url']));
                } else if (!empty($data['organization'])) {
                    $m->addText("项目从属于组织 " . $m->getLink($data['organization']['login'], "https://GitHub.com/" . $data['organization']['login']));
                }
                break;
            default:
                die("Action尚未支持");
        }
        return $m->message();
    }
}