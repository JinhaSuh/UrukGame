<?php

namespace App\repository;

use Exception;
use LogEntry;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TSocketPool;
use scribeClient;

class ScribeRepository
{
    private scribeClient $client;

    public function __construct()
    {
        $this->client = $this->create_scribe_client();
    }

    public function AssetLog(array $updated_user, int $item_type_id, int $item_count, string $action)
    {
        $msg = [
            "user_id" => $updated_user["user_id"],
            "level" => $updated_user["level"],
            "exp" => $updated_user["exp"],
            "fatigue" => $updated_user["fatigue"],
            "gold" => $updated_user["gold"],
            "pearl" => $updated_user["pearl"],
            "item_type_id" => $item_type_id,
            "count" => $item_count,
            "action" => $action
        ];

        $this->Log("asset", $msg);
    }

    public function Log(string $table, $msg)
    {
        date_default_timezone_set('Asia/Seoul');
        $date = date('Y-m-d h:i:s');
        $msg += ["log_time" => $date];
        $now_date = date("Ymd");
        $log[] = new LogEntry(array(
            'category' => $table . "_log_" . $now_date,
            'message' => json_encode($msg)
        ));
        $this->client->Log($log);
    }

    function create_scribe_client()
    {
        try {

            // Set up the socket connections
            $scribe_servers = array('192.168.152.1');
            $scribe_ports = array(1463);

            $sock = new TSocketPool($scribe_servers, $scribe_ports);
            $sock->setDebug(0);
            $sock->setSendTimeout(1000);
            $sock->setRecvTimeout(2500);
            $sock->setNumRetries(1);
            $sock->setRandomize(false);
            $sock->setAlwaysTryLast(true);
            $trans = new TFramedTransport($sock);
            $prot = new TBinaryProtocol($trans);

            // Create the client
            $scribe_client = new scribeClient($prot);

            // Open the transport (we rely on PHP to close it at script termination)
            $trans->open();
        } catch (Exception $e) {
            print "Unable to create global scribe client, received exception: $e \n";
            return null;
        }
        return $scribe_client;
    }
}
