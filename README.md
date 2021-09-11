# Github-Webhook-Notification
使用PHP撰写的github webhook 通知模块，用于腾讯云云函数

Demo: <https://api.ahdark.com/release/github-to-wechat>

请求时须携带参数`sendkey`，值为企业微信群机器人的Send Key值
例如：`https://api.ahdark.com/release/github-to-wechat?sendkey=8679xxxx-xxxx-xxxx-xxxx-xxxx3e2eab49`

> `api.ahdark.com`为测试环境，请勿依赖，不保证SLA！