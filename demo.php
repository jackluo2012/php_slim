<?php
function myHash($str) {
    $hash = 0;
    $s    = md5($str);
    $seed = 5;
    $len  = 32;
    for ($i = 0; $i < $len; $i++) {
        $hash = ($hash << $seed) + $hash + ord($s{$i});
    }
    return $hash & 0x7FFFFFFF;
}
class ConsistentHash {
    // server列表
    private $_server_list = array();
    // 延迟排序，因为可能会执行多次addServer
    private $_layze_sorted = FALSE;
    public function addServer($server) {
        $hash = myHash($server);
        $this->_layze_sorted = FALSE;

        if (!isset($this->_server_list[$hash])) {
            $this->_server_list[$hash] = $server;
        }

        return $this;
    }

    public function find($key) {
        // 排序
        if (!$this->_layze_sorted) {
            asort($this->_server_list);
            $this->_layze_sorted = TRUE;
        }

        $hash = myHash($key);
        $len  = sizeof($this->_server_list);
        if ($len == 0) {
            return FALSE;
        }

        $keys   = array_keys($this->_server_list);
        $values = array_values($this->_server_list);

        // 如果不在区间内，则返回最后一个server
        if ($hash <= $keys[0] || $hash >= $keys[$len - 1]) {
            return $values[$len - 1];
        }

        foreach ($keys as $key=>$pos) {
            $next_pos = NULL;
            if (isset($keys[$key + 1]))
            {
                $next_pos = $keys[$key + 1];
            }

            if (is_null($next_pos)) {
                return $values[$key];
            }

            // 区间判断
            if ($hash >= $pos && $hash <= $next_pos) {
                return $values[$key];
            }
        }
    }
}

$consisHash = new ConsistentHash();
$consisHash->addServer("serv1")->addServer("serv2")->addServer("server3");
echo "key1 at " . $consisHash->find("key1") . ".\n";
echo "key2 at " . $consisHash->find("key2") . ".\n";
echo "key3 at " . $consisHash->find("key3") . ".\n";
echo myHash('key1');
echo "<br />";
echo myHash('key2');
echo "<br />";
echo myHash('key3');
echo "<br />";