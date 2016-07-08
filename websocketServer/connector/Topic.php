<?php

namespace connector;


/**
 * Description of Topic
 *
 * @author sheldon
 */
class Topic {

    private $topics;
    private $mongoManager;

    public function __construct($mongoDbManager) {
        $this->mongoManager = $mongoDbManager->getManager();
        $this->topics=array();

        $query = new \MongoDB\Driver\Query([], []);
        $readPreference = new \MongoDB\Driver\ReadPreference(\MongoDB\Driver\ReadPreference::RP_PRIMARY);
        $cursor = $this->mongoManager->executeQuery("talkshow.topics", $query, $readPreference);

        foreach ($cursor as $document) {
            //array_push($this->topics, $document->topic=>array());
            $this->topics[$document->topic]=array();
        }
        
    }
    
    
    public function getTopis()
    {
        return $this->topics;
    }
    
    
    public function joinTopic($fd,$topic) {
        if(isset($this->topics[$topic]))
        {
            $this->topics[$topic][]=$fd;
            if(DEBUG){echo "fd{$fd} joined topic $topic \n";}
        }
    }
    
    
    public function getTopicClient($topic) {
        if(isset($this->topics[$topic]))
        {
            return $this->topics[$topic];
        }
    }
    
    public function leaveTopic($fd,$topic)
    {
        if(isset($this->topics[$topic]))
        {
            $topic=$this->topics[$topic];
            $clientCount=count($topic);
            for ($i=0;$i<$clientCount;$i++) {
                if($topic[$i]==$fd)
                {
                    array_splice($topic,$i,1);
                    if(DEBUG){echo "fd{$fd} leaved topic $topic \n";}
                    break;
                }
            }
        }
    }
    
    public function leaveAllTopic($fd) {
        foreach ($this->topics as $topic => $clients) {
            $clientCount=count($clients);
            for($i=0;$i<$clientCount;$i++)
            {
                if($clients[$i]==$fd)
                {
                    array_splice($this->topics[$topic],$i,1);
                }
            }
        }
        if(DEBUG){echo "fd{$fd} leaved all topic.\n";}
    }

}
