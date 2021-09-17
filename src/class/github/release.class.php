<?php

namespace GitHub;

use Exception;
use stdClass;
use WeWork\markdown;

class release extends github
{
    function __construct($data = null)
    {
        $this->setEvent("release");
        if ($data != null) {
            $this->setData($data);
        }
    }

    /**w
     * @throws Exception
     */
    public function getMessage(): string {
        if (!$this->isSet()) {
            throw new Exception("未设置data或event");
        }
        $data = $this->data;
        $action = $data['action'];
        $message = new markdown();

        switch ($action) {
            case "released": //发布
                // 初始化
                $release = new stdClass();
                $release->web_url = $data['release']['html_url'];
                $release->version = $data['release']['tag_name'];
                $release->name = $data['release']['name'];
                $release->describe = $data['release']['body'];
                $release->download->tar = $data['release']['tarball_url'];
                $release->download->zip = $data['release']['zipball_url'];

                $sender = new stdClass();
                $sender->name = $data['sender']['login'];
                $sender->url = $data['sender']['html_url'];

                $repository = new stdClass();
                $repository->name = $data['repository']['name'];
                $repository->isPrivate = (bool)$data['repository']['private'];
                $repository->url = $data['repository']['html_url'];


                $message->addTitle("有新的**Release**发布: ".$message->getLink($release->name,$release->web_url));
                $message->addText($release->describe);
                $message->addText("版本: ".$release->version);
                $message->addLine();
                $message->addText("发布者: ".$message->getLink($sender->name,$sender->url));
                $message->addText("仓库: ".$message->getLink($repository->name,$repository->url));
                if(!$repository->isPrivate) {
                    $message->addLine();
                    $message->addText($message->getLink("下载tar.gz格式源代码",$release->download->tar));
                    $message->addText($message->getLink("下载zip格式源代码",$release->download->zip));
                }
                break;
            case "prereleased": //预发布
                // 初始化
                $release = new stdClass();
                $release->web_url = $data['release']['html_url'];
                $release->name = $data['release']['name'];
                $release->describe = $data['release']['body'];
                $release->download->tar = $data['release']['tarball_url'];
                $release->download->zip = $data['release']['zipball_url'];

                $sender = new stdClass();
                $sender->name = $data['sender']['login'];
                $sender->url = $data['sender']['html_url'];

                $repository = new stdClass();
                $repository->name = $data['repository']['name'];
                $repository->isPrivate = (bool)$data['repository']['private'];
                $repository->url = $data['repository']['html_url'];


                $message->addTitle("有新的**Release**预发布: ".$message->getLink($release->name,$release->web_url));
                $message->addText($release->describe);
                $message->addLine();
                $message->addText("发布者: ".$message->getLink($sender->name,$sender->url));
                $message->addText("仓库: ".$message->getLink($repository->name,$repository->url));
                if(!$repository->isPrivate) {
                    $message->addLine();
                    $message->addText($message->getLink("下载tar.gz格式源代码",$release->download->tar));
                    $message->addText($message->getLink("下载zip格式源代码",$release->download->zip));
                }
                break;
            case "edited": //编辑
                // 初始化
                $release = new stdClass();
                $release->web_url = $data['release']['html_url'];
                $release->name = $data['release']['name'];

                $sender = new stdClass();
                $sender->name = $data['sender']['login'];
                $sender->url = $data['sender']['html_url'];

                $repository = new stdClass();
                $repository->name = $data['repository']['name'];
                $repository->url = $data['repository']['html_url'];


                $message->addTitle("**Release**被编辑: ".$message->getLink($release->name,$release->web_url));
                $message->addText("操作者: ".$message->getLink($sender->name,$sender->url));
                $message->addText("仓库: ".$message->getLink($repository->name,$repository->url));
                break;
            case "deleted": //删除
                // 初始化
                $release = new stdClass();
                $release->web_url = $data['release']['html_url'];
                $release->name = $data['release']['name'];

                $sender = new stdClass();
                $sender->name = $data['sender']['login'];
                $sender->url = $data['sender']['html_url'];

                $repository = new stdClass();
                $repository->name = $data['repository']['name'];
                $repository->url = $data['repository']['html_url'];


                $message->addTitle("**Release**被删除: ".$message->getLink($release->name,$release->web_url));
                $message->addText("操作者: ".$message->getLink($sender->name,$sender->url));
                $message->addText("仓库: ".$message->getLink($repository->name,$repository->url));
                break;
            default:
                die();
        }
        return $message->message();
    }
}