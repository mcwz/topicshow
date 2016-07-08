# topicshow

## introduce

这是一个话题聊天程序。选择主话题后，在聊天的过程中，如果觉得某句话值得深聊，可以点击生成子话题，然后在子话题出继续聊，达到分组效果

## requirement

PHP 5.6+
apache或者nginx
redis 3.2+
mongodb 3.2+
swoole 1.8.7+

## install

* 首先安装好 mongodb redis。并启动。

* 安装apache或者nginx，安装完毕后，配置\talkshow\为web根路径。并启动web服务。

* 然后php swoole。在PHP.ini里配置session交由redis处理。

* 在\talkshow\application\config\mongo.php里配置mongodb信息。

* 在\websocketServer\config.php里配置其他信息。

* 进入到\websocketServer\下执行php ws_index.php

* 访问localhost
