<?php

namespace controllers;

use core\ControllerBase;

class Command extends ControllerBase {

    public static function refresh($objects) {
        $redisConn = $objects['redisConn'];
        $frame = $objects['frame'];
        $redisConn->refreshFdSession($frame->fd);
    }

    public static function jointopic($objects) {
        $frame = $objects['frame'];
        $topicObj = $objects['topic'];
        $messageBody = json_decode($frame->data);
        $joinningTopic = $messageBody->data->topic;
        $topicObj->joinTopic($frame->fd, $joinningTopic);
    }

    public static function leavetopic($objects) {
        $frame = $objects['frame'];
        $topicObj = $objects['topic'];
        $messageBody = json_decode($frame->data);
        $leavingTopic = $messageBody->data->topic;
        $topicObj->leaveTopic($frame->fd, $leavingTopic);
    }

    public static function getsubtopicid($objects) {
        $frame = $objects['frame'];
        $topicObj = $objects['topic'];
        $server = $objects['server'];
        $mongoManager = $objects['mongoManager']->getManager();
        $messageBody = json_decode($frame->data);
        $topicAsking = $messageBody->data->topic;
        $word = $messageBody->data->word;

        $addid = "";
        if ($topicAsking != '' && key_exists($topicAsking, $topicObj->getTopis())) {

            $query = new \MongoDB\Driver\Query(['word' => $word]);
            $rows = $mongoManager->executeQuery('talkshow.subtopics', $query)->toArray();

            if (count($rows)) {
                foreach ($rows as $r) {
                    $addid = $r->_id . '';
                }

            } else {

                $bulk = new \MongoDB\Driver\BulkWrite(['ordered' => true]);
                $addid = $bulk->insert(['mainTopic' => $topicAsking, 'word' => $word, 'time' => time()]);

                $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
                try {
                    $result = $mongoManager->executeBulkWrite('talkshow.subtopics', $bulk, $writeConcern);
                    $addid.="";

                } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
                    $result = $e->getWriteResult();

                    if ($writeConcernError = $result->getWriteConcernError()) {
                        printf("%s (%d): %s\n", $writeConcernError->getMessage(), $writeConcernError->getCode(), var_export($writeConcernError->getInfo(), true)
                        );
                    }

                    foreach ($result->getWriteErrors() as $writeError) {
                        printf("Operation#%d: %s (%d)\n", $writeError->getIndex(), $writeError->getMessage(), $writeError->getCode()
                        );
                    }
                } catch (\MongoDB\Driver\Exception\Exception $e) {
                    printf("Other error: %s\n", $e->getMessage());
                    exit;
                }
            }

            if ($addid != "") {
                $message = '{"route":"command/getsubtopicid","data":{"subtopicid":"' . $addid . '","word":"' . $word . '"},"serverCallback":"' . $messageBody->serverCallback . '"}';
                $server->push($frame->fd, $message);
            }
        }
    }

}
