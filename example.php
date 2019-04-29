<?php

include __DIR__. '/vendor/autoload.php';

use QAlliance\CrontabManager\Factory;

$cronJobs = [
    '3 */4 * * * /home/dr-pro/dev/bittrex-logger/bin/console bittrex:fetch --verbose --test',
    '9 */12 * * 0 /home/test/keke/vendor/bin/foobar run --die',
    '11 1 * * 1 /usr/bin/php /var/www/sample.q-software.com/bin/console list app',
];

// use factory to create a writer
$writer = Factory::createWriter('www-data');

// update the managed part of crontab with $cronJobs, keeping the other cron jobs intact
$writer->updateManagedCrontab($cronJobs);
