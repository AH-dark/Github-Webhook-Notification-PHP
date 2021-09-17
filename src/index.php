<?php

use GitHub\issues;
use GitHub\pullrequest;
use GitHub\push;
use WeWork\GroupBot;

require_once "./wework.php";
require_once "./markdown.class.php";
require_once "./github/autoload.php";

function object_to_array(object $obj): array
{
    $_arr = get_object_vars($obj);
    $arr = null;
    foreach ($_arr as $key => $val) {
        $val = (is_array($val)) || is_object($val) ? object_to_array($val) : $val;
        $arr[$key] = $val;
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
    $body = json_decode($event->body, 1);

    switch ($githubEvent) {
        case "ping":
            $res = $wechat->sendMessage("收到Ping请求，字符串为" . $body['zen'] . "，hookId为" . $body['hook_id'] . "。");
            break;
        case "push":
            $github = new push($body);
            try {
                $message = $github->getMessage();
                echo $message;
                $res = $wechat->sendMarkdownMessage($message);
            } catch (Exception $e) {
                $wechat->sendMessage($e->getCode() . "error: " . $e->getMessage());
            }
            break;
        case "pull_request":
            $github = new pullrequest($body);
            try {
                $message = $github->getMessage();
                echo $message;
                $res = $wechat->sendMarkdownMessage($message);
            } catch (Exception $e) {
                $wechat->sendMessage($e->getCode() . "error: " . $e->getMessage());
            }
            break;
        case "issues":
            $github = new issues($body);
            try {
                $message = $github->getMessage();
                echo $message;
                $res = $wechat->sendMarkdownMessage($message);
            } catch (Exception $e) {
                $wechat->sendMessage($e->getCode() . "error: " . $e->getMessage());
            }
            break;
    }

    return [
        "isBase64Encoded" => false,
        "statusCode" => isset($res['error']) ? 500 : 200,
        "headers" => '{"Content-Type":"application/json"}',
        "body" => [
            "code" => isset($res['error']) ? 500 : 200,
            "message" => $res['error'] ?? "OK"
        ]
    ];
}
