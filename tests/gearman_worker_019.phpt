--TEST--
GearmanWorker::setSSL(), gearman_worker_set_ssl()
--SKIPIF--
<?php if (!extension_loaded("gearman")) print "skip";
require_once('skipifconnect.inc');
?>
--FILE--
<?php 

$worker = new GearmanWorker();
print "GearmanWorker::setSSL(false): " . ($worker->setSSL(false) ? 'Success' : 'Failure') . PHP_EOL;

$worker2 = gearman_worker_create();
print "gearman_worker_set_ssl (Procedural): " . (gearman_worker_set_ssl($worker2, false) ? 'Success' : 'Failure') . PHP_EOL;

print "OK";
?>
--EXPECT--
GearmanWorker::setSSL(false): Success
gearman_worker_set_ssl (Procedural): Success
OK
