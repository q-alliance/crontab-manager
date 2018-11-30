<?php

include __DIR__. '/vendor/autoload.php';

use QAlliance\CrontabManager\Reader;
use QAlliance\CrontabManager\Writer;

$cronJobs = [
    '3 */4 * * * /home/test/dev/bittrex-logger/bin/console bittrex:fetch --verbose --test',
    '9 */12 * * 0 /home/test/keke/vendor/bin/foobar run --die',
    '11 1 * * 1 /usr/bin/php /var/www/sample.q-software.com/bin/console list app',
];

// fetch current user, as an example - or use plain string with any username
$myLocalUsername = shell_exec('id -u -n');

// create a reader
$reader = new Reader($myLocalUsername);

// create a writer
$writer = new Writer($reader);

// update the managed part of crontab with $cronJobs, keeping the other cron jobs intact
$writer->updateManagedCrontab($cronJobs);