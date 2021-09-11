<?php

use WeWork\GroupBot;

require "./wework.php";

function object_to_array(object $obj): array
{
    $_arr= get_object_vars($obj);
    $arr = null;
    foreach($_arr as $key=>$val){
        $val=(is_array($val))||is_object($val)?object_to_array($val):$val;
        $arr[$key]=$val;
    }
    return $arr;
}

/**
 * @describe Main
 * @param object $event
 * @param object $context
 * @return array
 * @noinspection PhpMissingReturnTypeInspection
 * @noinspection PhpMissingParamTypeInspection
 * @noinspection PhpUnusedParameterInspection
 */
function main_handler($event, $context)
{
    $headers = object_to_array($event->headers);
    $githubEvent = $headers['x-github-event'];
    $wechat = new GroupBot();
    $wechat->setSendKey($event->queryString->sendkey);
    $body = json_decode($event->body,1);

    switch($githubEvent) {
        case "ping":
            $res = $wechat->sendMessage("收到Ping请求，字符串为".$body['zen']."，hookId为".$body['hook_id']."。");
            break;
        case "push":
            $message = <<<EOF
### 有新的Push事件

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
            $res = $wechat->sendMarkdownMessage($message);
            break;
    }

    return [
        "isBase64Encoded" => false,
        "statusCode" => isset($res['error'])?500:200,
        "headers" => '{"Content-Type":"application/json"}',
        "body" => [
            "code" => isset($res['error'])?500:200,
            "message" => $res['error'] ?? "OK"
        ]
    ];
}
