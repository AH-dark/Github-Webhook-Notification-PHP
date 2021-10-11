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

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $project_card['project_url'],
            CURLOPT_HTTPHEADER => json_encode([
                "Accept" => "application/vnd.github.v3+json",
                "Authorization" => "Basic " . base64_encode($this->config['username'] . ":" . $this->config['password'])
            ]),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_RETURNTRANSFER => true
        ]);
        $res = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($res, true);
        $isGetProjectSuccess = !empty($res['name']) && !empty($res['html_url']);

        if(!$isGetProjectSuccess) { echo "CURL错误：".$res['message'].PHP_EOL; }

        switch ($data['action']) {
            case "created":
                $m->addTitle("有新的项目卡片创建");
                $m->addText("所在项目：" . ($isGetProjectSuccess ? $m->getLink($res['name'], $res['html_url']) : $m->getColorText("项目获取失败", "warning")));
                $m->addText("内容：");
                $m->addQuote($project_card['note']);
                $m->addLine();
                $m->addText("创建者：" . $m->getLink($project_card['creator']['login'], $project_card['creator']['html_url']));
                if($isGetProjectSuccess) {
                    $m->addLine();
                    $m->addText($m->getLink("查看项目",$res['html_url']));
                }
                break;
            case "deleted":
                $m->addTitle("有新的项目卡片被删除");
                $m->addText("所在项目：" . ($isGetProjectSuccess ? $m->getLink($res['name'], $res['html_url']) : $m->getColorText("项目获取失败", "warning")));
                $m->addText("内容：");
                $m->addQuote($project_card['note']);
                $m->addLine();
                $m->addText("操作者：" . $m->getLink($data['sender']['login'], $data['sender']['html_url']));
                break;
            default:
                die("Action尚未支持");
        }
        unset($res);
        return $m->message();
    }
}