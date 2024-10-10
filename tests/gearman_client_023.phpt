--TEST--
GearmanClient::setSSL(), gearman_client_set_ssl()
--SKIPIF--
<?php if (!extension_loaded("gearman")) print "skip";
require_once('skipifconnect.inc');
?>
--FILE--
<?php 

$client = new GearmanClient();
print "GearmanClient::setSSL(false): " . ($client->setSSL(false) ? 'Success' : 'Failure') . PHP_EOL;

$client2 = gearman_client_create();
print "gearman_client_set_ssl (Procedural): " . (gearman_client_set_ssl($client2, false) ? 'Success' : 'Failure') . PHP_EOL;

print "OK";
?>
--EXPECT--
GearmanClient::setSSL(false): Success
gearman_client_set_ssl (Procedural): Success
OK
