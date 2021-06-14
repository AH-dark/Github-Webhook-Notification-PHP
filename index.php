<?php

namespace Main;

use Github\GithubHandle;

function main_handler($event, $context)
{
	$handle = json_decode(urldecode($event));
	$_Event = $handle['headers']['X-GitHub-Event'];

	// 非Github请求
	if ($_Event == null) {
		return json_encode([
			"isBase64Encoded" => false,
			"statusCode" => 500,
			"headers" => [
				"Content-Type" => "text/html"
			],
			"body" => "<html><body><p>Not a Github Webhook handle.</p></body></html>"
		]);
	}

	// 验证UA
	if (strpos($handle['headers']['GitHub-Hookshot'], 'GitHub-Hookshot') === false) {
		return json_encode([
			"isBase64Encoded" => false,
			"statusCode" => 500,
			"headers" => [
				"Content-Type" => "text/html"
			],
			"body" => "<html><body><p>Error User Agent</p></body></html>"
		]);
	}

	$Github = new GithubHandle;
	$Github->Handle($event);
}
