<?php

namespace Notification\Wxwork\GroupRobot;

use Notification\Notification;

use function Tool\Img2Base64;
use function Tool\Img2MD5;

class WxworkGroupRobot extends Notification
{
	/**
	 * @var string BaseURL 企业微信群机器人通知地址
	 * @link https://work.weixin.qq.com/api/doc/90000/90136/91770
	 * @api
	 */
	const BaseURL = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send';

	/**
	 * @var string RobotID 企业微信机器人ID
	 * @link https://work.weixin.qq.com/api/doc/90000/90136/91770
	 */
	var $RobotID;

	/**
	 * 向企业微信发送Post请求
	 * @param array $data 数组数据
	 * @return array 经json_decode的数组数据
	 */
	private function SendPost(array $data)
	{
		$data = json_encode($data);
		$headerArray = array("Content-type:application/json;charset='utf-8'", "Accept:application/json");
		$ch = curl_init(self::BaseURL . '?key=' . self::$RobotID);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output, true);
	}

	/**
	 * 发送文字消息
	 * @link https://work.weixin.qq.com/api/doc/90000/90136/91770#%E6%96%87%E6%9C%AC%E7%B1%BB%E5%9E%8B
	 * @param string $content 文字消息内容
	 * @param array $mentioned_list userid的列表，提醒群中的指定成员(@某个成员)，@all表示提醒所有人
	 * @param array $mentioned_mobile_list 手机号列表，提醒手机号对应的群成员(@某个成员)，@all表示提醒所有人
	 */
	public function TextNotice(string $content, array $mentioned_list = null, array $mentioned_mobile_list = null)
	{
		$data = [
			"msgtype" => "text",
			"text" => [
				"content" => $content,
			]
		];
		if ($mentioned_list != null)
			$data['text']['mentioned_list'] = $mentioned_list;
		if ($mentioned_mobile_list	 != null)
			$data['text']['mentioned_mobile_list'] = $mentioned_mobile_list;

		return $this->SendPost($data);
	}

	/**
	 * 发送Markdown消息
	 * @link https://work.weixin.qq.com/api/doc/90000/90136/91770#markdown%E7%B1%BB%E5%9E%8B
	 * @param string $content 文字消息内容
	 */
	public function MarkdownNotice(string $content)
	{
		$data = [
			"msgtype" => "markdown",
			"markdown" => [
				"content" => $content,
			]
		];

		return $this->SendPost($data);
	}

	/**
	 * 发送图片消息
	 * @link https://work.weixin.qq.com/api/doc/90000/90136/91770#%E5%9B%BE%E7%89%87%E7%B1%BB%E5%9E%8B
	 * @param string $imgurl 图片URL地址
	 */
	public function ImageNotice(string $imgurl)
	{
		$data = [
			"msgtype" => "image",
			"image" => [
				"base64" => Img2Base64($imgurl),
				"md5" => Img2MD5($imgurl)
			]
		];

		return $this->SendPost($data);
	}
}
