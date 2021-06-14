<?php

namespace Github;

use Github\Push\GithubPush;
use Notification\Wxwork\GroupRobot\WxworkGroupRobot;

class GithubHandle
{
	/**
	 * Action 中文翻译
	 */
	const ActionWords = [
		"opened" => "发起",
		"closed" => "关闭",
		"reopened" => "重新发起",
		"edited" => "更新",
		"merge" => "合并",
		"created" => "创建",
		"requested" => "请求",
		"completed" => "完成",
		"synchronize" => "同步更新",
		"created" => "创建",
		"deleted" => "删除",
		"renamed" => "重命名",
		"added" => "加入",
		"removed" => "退出",
		"edited" => "修改",
		"reopened" => "重开",
		"published" => "发布",
		"unpublished" => "删除",
		"prereleased" => "预发布"
	];

	/**
	 * @var GithubEvent Github触发事件
	 */
	var $GithubEvent;

	/**
	 * @var Sender 触发事件的用户
	 * @var Sender->login 用户昵称
	 * @var Sender->id 用户ID
	 * @var Sender->avatar_url 用户头像
	 * @var Sender->html_url 用户页面
	 * @var Sender->type 用户类型
	 */
	var $Sender;

	/**
	 * @var Body 触发器Body部分
	 */
	var $Body;

	var $wxwork = new WxworkGroupRobot;

	public function Handle(string $event)
	{
		$data = json_decode(urldecode($event));
		self::$Body = $data['body'];
		self::$GithubEvent = $data['headers']['X-GitHub-Event'];
		self::$wxwork->RobotID = $data['queryString']['id'];
		switch (self::$GithubEvent) {
			case "push":
				$github = new GithubPush;
				$github->PushHandle();
				break;
		}
	}
}
