--TEST--
Worker exit() mid-callback returns job to queue (issue #26)
--SKIPIF--
<?php
require_once('skipif.inc');
require_once('skipifconnect.inc');
?>
--FILE--
<?php
require_once('connect.inc');

$func = 'issue26_' . getmypid() . '_' . time();

/* Submit a background job */
$client = new GearmanClient();
$client->addServer($host, $port);
$handle = $client->doBackground($func, 'test_payload');
if ($client->returnCode() !== GEARMAN_SUCCESS) {
    die("Could not submit job");
}

/* Worker 1: grabs the job and exit()s without completing it */
$pid1 = pcntl_fork();
if ($pid1 === 0) {
    $w = new GearmanWorker();
    $w->addServer($host, $port);
    $w->addFunction($func, function($job) {
        exit(1);
    });
    $w->work();
    exit(0);
}
pcntl_waitpid($pid1, $status1, 0);

/* Brief pause for gearmand to detect the disconnect */
usleep(500000);

/* Worker 2: should receive the retried job */
$pid2 = pcntl_fork();
if ($pid2 === 0) {
    $w = new GearmanWorker();
    $w->addServer($host, $port);
    $w->setTimeout(10000);
    $w->addFunction($func, function($job) {
        echo "payload: " . $job->workload() . PHP_EOL;
        return "done";
    });
    $ret = $w->work();
    exit($ret ? 0 : 2);
}
pcntl_waitpid($pid2, $status2, 0);

if (pcntl_wexitstatus($status2) === 0) {
    echo "PASS" . PHP_EOL;
} else {
    echo "FAIL: job was not retried" . PHP_EOL;
}
--EXPECT--
payload: test_payload
PASS
