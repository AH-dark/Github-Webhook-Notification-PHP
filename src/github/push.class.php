<?php

namespace GitHub;

use Exception;

class push extends github
{
    function __construct($data = null) {
        $this->setEvent("push");
        if($data!=null) {
            $this->setData($data);
        }
    }

    /**
     * @throws Exception
     * @return string
     * @noinspection HtmlDeprecatedTag
     */
    public function getMessage(): string
    {
        if(!$this->isSet()) {   throw new Exception("未设置data或event");  }
        $body = $this->data;
        $message = <<<EOF
### 有新的<font color=\"warning\">Push</font>事件

仓库: [{$body['repository']['name']}]({$body['repository']['html_url']})

推送者: [{$body['pusher']['name']}({$body['pusher']['email']})]({$body['sender']['html_url']})

EOF;
        foreach ($body['commits'] as $commit) {
            $message .= <<<EOF

> Commit {$commit['id']}: 
> 
> <font color=\"comment\">{$commit['message']}</font>
> 
> on {$commit['timestamp']}
> 
> [查看详情]({$commit['url']})

EOF;
        }
        $message .= "\nSHA: ".$body['after'];
        return $message;
    }
}