<?php

namespace Tool;

//Unicode加密
function UnicodeEncode($str)
{
	preg_match_all('/./u', $str, $matches);

	$unicodeStr = "";
	foreach ($matches[0] as $m) {
		$unicodeStr .= "&#" . base_convert(bin2hex(iconv('UTF-8', "UCS-4", $m)), 16, 10);
	}
	return $unicodeStr;
}

//Unicode解密
function UnicodeDecode($unicode_str)
{
	$json = '{"str":"' . $unicode_str . '"}';
	$arr = json_decode($json, true);
	if (empty($arr)) return '';
	return $arr['str'];
}

//图片->base64 转换
function Img2Base64($url)
{
	$imgInfo = getimagesize($url);
	return 'data:' . $imgInfo['mime'] . ';base64,' . base64_encode(file_get_contents($url));
}

//图片->MD5 转换
function Img2MD5($url)
{
	return md5_file($url);
}
