<?php

include __DIR__. '/vendor/autoload.php';

use QAlliance\CrontabManager\Factory;

$cronJobs = [
    '3 */4 * * * /home/test/dev/bittrex-logger/bin/console bittrex:fetch --verbose --test',
    '9 */12 * * 0 /home/test/keke/vendor/bin/foobar run --die',
    '11 1 * * 1 /usr/bin/php /var/www/sample.q-software.com/bin/console list app',
];

// create simple factory
$factory = new Factory();
// or factory with username and temp file
// $factory = new Factory('www-data', '/tmp/fill_me_up');
$writer = $factory->createWriter();

// update the managed part of crontab with $cronJobs, keeping the other cron jobs intact
$writer->updateManagedCrontab($cronJobs);
