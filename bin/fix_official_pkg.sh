#!/usr/bin/env sh

if [[ `uname  -a` =~ "Darwin" ]];then
   sed -i '' "s#instanceId = NULL#instanceId#g" vendor/aliyunmq/mq-http-sdk/MQ/MQConsumer.php
   sed -i '' "s#instanceId = NULL#instanceId#g" vendor/aliyunmq/mq-http-sdk/MQ/MQProducer.php
   sed -i '' 's#if ($e->hasResponse()) {#if (method_exists($e, "hasResponse") \&\& method_exists($e, "getResponse") \&\& $e->hasResponse() \&\& true) {#g' vendor/aliyunmq/mq-http-sdk/MQ/Responses/MQPromise.php
else
   sed -i "s#instanceId = NULL#instanceId#g" vendor/aliyunmq/mq-http-sdk/MQ/MQConsumer.php
   sed -i "s#instanceId = NULL#instanceId#g" vendor/aliyunmq/mq-http-sdk/MQ/MQProducer.php
   sed -i 's#if ($e->hasResponse()) {#if (method_exists($e, "hasResponse") \&\& method_exists($e, "getResponse") \&\& $e->hasResponse() \&\& true) {#g' vendor/aliyunmq/mq-http-sdk/MQ/Responses/MQPromise.php
fi
