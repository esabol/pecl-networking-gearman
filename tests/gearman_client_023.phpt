--TEST--
GEARMAN_CLIENT_STOP_WAIT_ON_SIGNAL constant and addOptions()
--SKIPIF--
<?php
if (!extension_loaded("gearman")) print "skip";
if (!defined("GEARMAN_CLIENT_STOP_WAIT_ON_SIGNAL")) print "skip libgearman too old";
?>
--FILE--
<?php
$client = new GearmanClient();
$before = $client->options();
$client->addOptions(GEARMAN_CLIENT_STOP_WAIT_ON_SIGNAL);
$after = $client->options();
var_dump(($after & GEARMAN_CLIENT_STOP_WAIT_ON_SIGNAL) !== 0);
$client->removeOptions(GEARMAN_CLIENT_STOP_WAIT_ON_SIGNAL);
$removed = $client->options();
var_dump(($removed & GEARMAN_CLIENT_STOP_WAIT_ON_SIGNAL) === 0);
print "OK";
?>
--EXPECT--
bool(true)
bool(true)
OK
