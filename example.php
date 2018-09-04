<?php

include __DIR__. '/vendor/autoload.php';

use QAlliance\CrontabManager\Reader;
use QAlliance\CrontabManager\Writer;

$cronJobs = [
    '3 */4 * * * /home/test/dev/bittrex-logger/bin/console bittrex:fetch --verbose',
    '9 */12 * * 0 /home/test/keke/vendor/bin/foobar run --die',
    '11 1 * * 1 /usr/bin/php /var/www/sample.q-software.com/bin/console app:timerweekteamwork',
];

$reader = new Reader('zwer');
$writer = new Writer($reader);

$writer->updateManagedCrontab($cronJobs);







