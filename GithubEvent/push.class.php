<?php

namespace Github\Push;

use Github\GithubHandle;
use Notification\Notification;
use Notification\Wxwork\GroupRobot\WxworkGroupRobot;

class GithubPush extends GithubHandle
{
	/**
	 * 提交的仓库分支
	 * @var string $ref
	 */
	var $ref;

	/**
	 * 提交的仓库名 (组织/仓库)
	 * @var string $repository_name
	 */
	var $repository_name;

	/**
	 * 是否为私有
	 * @var bool $private
	 */
	var bool $private;

	/**
	 * 仓库页面地址
	 * @var string $repository_html_url
	 */
	var string $repository_html_url;

	/**
	 * 提交信息
	 * @var array commits[]
	 */
	var $commits;

	/**
	 * 在 parent::$Body 中读取并设置变量
	 */
	protected function SetData()
	{
		self::$ref = parent::$Body['ref'];
		self::$repository_name = parent::$Body['repository']['full_name'];
		self::$private = parent::$Body['repository']['private'];
		self::$repository_html_url = parent::$Body['repository']['html_url'];
		self::$commits = parent::$Body['commits'];
	}

	public function PushHandle()
	{
		$this->SetData();

		$message = '<font color="warning">**仓库' . self::$repository_name . '收到一次push提交**</font>\n';
		$message .= '\t分支: ' . self::$ref . '\n';
		$message .= '\t最新提交信息: \n';

		foreach (self::$commits as $commit) {
			$message .= '\t\t[' . $commit['message'] . ' >' . $commit['timestamp'] . '](' . $commit['url'] . ')';
			foreach ($commit['added'] as $added) {
				$message .= '\t\t\t<font color="info">新增文件：' . $added . '</font>';
			}
			foreach ($commit['modified'] as $modified) {
				$message .= '\t\t\t<font color="comment">修改文件：' . $modified . '</font>';
			}
			foreach ($commit['removed'] as $removed) {
				$message .= '\t\t\t<font color="warning">移除文件：' . $removed . '</font>';
			}
			$message .= '\t\t\t提交者: [' . $commit['author']['name'] . '](mailto:' . $commit['author']['email'] . ')';
		}

		if (self::$private == true)
			$message .= '> 该仓库为私有仓库';

		$request = parent::$wxwork->MarkdownNotice($message);
		$code = $request['errcode'];
		if ($code == 0)
			return [
				"isBase64Encoded" => false,
				"statusCode" => 200,
				"headers" => [],
				"body" => "Success"
			];
		else {
			$errmsg = $request['errmsg'];
			return [
				"isBase64Encoded" => false,
				"statusCode" => $code,
				"headers" => [],
				"body" => $errmsg
			];
		}
	}
}
