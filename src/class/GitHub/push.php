<?php

namespace GitHub;

use Exception;

class push extends github implements GitHubEvent
{
    function __construct($data = null)
    {
        $this->setEvent("push");
        parent::__construct($data);
    }

    /**
     * @return string
     * @noinspection HtmlDeprecatedTag
     * @throws Exception
     * @noinspection HtmlDeprecatedAttribute
     */
    public function getMessage(): string
    {
        if (!$this->isSet()) {
            throw new Exception("未设置data或event");
        }
        $body = $this->data;
        $message = <<<EOF
### 有新的<font color="warning">Push</font>事件
仓库: [{$body['repository']['name']}]({$body['repository']['html_url']})
推送者: [{$body['pusher']['name']}({$body['pusher']['email']})]({$body['sender']['html_url']})
EOF;
        foreach ($body['commits'] as $commit) {
            $timestamp = UTC_to_time($commit['timestamp']);
            $message .= <<<EOF

> Commit {$commit['id']}: 
> {$commit['message']}
> on $timestamp
> [查看详情]({$commit['url']})

EOF;
        }
        $message .= "\nSHA: " . $body['after'];
        return $message;
    }
}