<?php

if(isset($_SESSION['count']))
$_SESSION['count']=$_SESSION['count']+1;
else
$_SESSION['count']=1;
$_SESSION['name']=1;
$_SESSION['test']="test";
session_write_close();
echo '<br/>';
$_SESSION['test']="test1";
$redis = new redis();
$redis->connect('127.0.0.1', 6379);
//redis用session_id作为key并且是以string的形式存储
$session=$redis->get('PHPREDIS_SESSION:' . session_id());
//$session=unserialize($session);
echo serialize(12)."<br/>";
print_r($session);

include_once "Session.php";
$sessionObj=new Session();
$session=$sessionObj->unserialize($session);
echo "***************************<br/>";
print_r($session);

/*
class MySessionHandler implements SessionHandlerInterface
{
    private $savePath;

    public function open($savePath, $sessionName)
    {
        $this->savePath = $savePath;
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0777);
        }

        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        return (string)@file_get_contents("$this->savePath/sess_$id");
    }

    public function write($id, $data)
    {
        return file_put_contents("$this->savePath/sess_$id", $data) === false ? false : true;
    }

    public function destroy($id)
    {
        $file = "$this->savePath/sess_$id";
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    public function gc($maxlifetime)
    {
        foreach (glob("$this->savePath/sess_*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }
}

$handler = new MySessionHandler();
session_set_save_handler($handler, true);
session_start();




$_SESSION['count']=$_SESSION['count']+1;
echo $_SESSION['count'];
*/