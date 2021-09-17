<?php

namespace WeWork;

/**
 * @describe 企业微信Markdown类型消息
 * @see https://work.weixin.qq.com/api/doc/90000/90136/91770#h3-markdown-
 * @copyright AHDark
 * @since 7.2
 */
class markdown
{
    protected $message = "";

    /**
     * @param string $title
     * @param string $text
     */
    function __construct(string $title = "", string $text = "")
    {
        if ($title != "") {
            $this->addTitle($title);
        }
        if ($text != "") {
            $this->addMessage($text);
        }
    }

    /**
     * @describe 添加一段标题
     * @param string $title 标题内容
     * @param int $level '#'数量
     * @return bool
     */
    public function addTitle(string $title, int $level = 3): bool
    {
        if ($level > 6) $level = 6;
        if ($level < 1) $level = 1;

        return $this->addMessage(str_repeat("#", $level) . " " . $title);
    }

    /**
     * @describe 添加一段消息
     * @param string $message
     * @return bool
     */
    protected function addMessage(string $message): bool
    {
        $this->message .= $message . "\n\n";
        return true;
    }

    /**
     * @describe 获取全部消息
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }

    /**
     * @describe 添加一段文字
     * @param string $text 文字内容
     * @return bool
     */
    public function addText(string $text = ""): bool
    {
        return $this->addMessage($text);
    }

    /**
     * @describe 添加空行
     * @return bool
     */
    public function addLine(): bool
    {
        return $this->addMessage("");
    }

    /**
     * @describe 添加一段列表
     * @param string|array $list
     * @return bool
     */
    public function addList($list): bool
    {
        if (gettype($list) == "string") {
            return $this->addMessage($list);
        } else if (gettype($list) == "array") {
            $return = "";
            foreach ($list as $k => $v) {
                if (is_int($k)) {
                    $return .= "- $v\n";
                } else {
                    $return .= "- $k: $v\n";
                }
            }
            return $this->addMessage(substr($return, 0, -1));
        } else {
            return false;
        }
    }

    /**
     * @describe 添加一段引用
     * @param string|array $list
     * @return bool
     */
    public function addQuote($list): bool
    {
        if (gettype($list) == "string") {
            return $this->addMessage($list);
        } else if (gettype($list) == "array") {
            $return = "";
            foreach ($list as $k => $v) {
                if (is_int($k)) {
                    $return .= "> $v\n";
                } else {
                    $return .= "> $k: $v\n";
                }
            }
            return $this->addMessage(substr($return, 0, -1));
        } else {
            return false;
        }
    }

    /**
     * @describe 返回链接格式
     * @param string $text 文字
     * @param string $url 链接
     * @return string Markdown格式超链接
     */
    public function getLink(string $text, string $url): string
    {
        return "[$text]($url)";
    }

    /**
     * @describe 返回代码块
     * @param string $code
     * @return string Markdown格式代码块
     */
    public function getCode(string $code): string
    {
        return "`$code`";
    }


    /**
     * @describe 返回特殊文字格式
     * @param string $text
     * @param string $color Expect "info","comment","warning"
     * @return string 企业微信文字加颜色
     * @noinspection HtmlDeprecatedTag
     * @noinspection HtmlDeprecatedAttribute
     */
    public function getColorText(string $text, string $color): string
    {
        if ($color === "info" || $color === "comment" || $color === "warning") {
            return "<font color=\"$color\">$text</font>";
        } else {
            return $text;
        }
    }
}