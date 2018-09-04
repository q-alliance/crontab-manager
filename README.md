# Q Alliance / Crontab Manager

### Installation:
```bash
$ composer require q-alliance/crontab-manager
```

### Usage:
```php
<?php

use Acrnogor\CrontabManager\Reader;
use Acrnogor\CrontabManager\Writer;

$cronJobsIWantToManage = [
    '3 */4 * * * /home/test/dev/bittrex-logger/bin/console bittrex:fetch --verbose',
    '9 */12 * * 0 /home/test/keke/vendor/bin/foobar run --die',
    '11 1 * * 1 /usr/bin/php /var/www/sample.q-software.com/bin/console app:timerweekteamwork',
];

$writer = new Writer(new Reader());
$writer->updateManagedCrontab($cronJobsIWantToManage);

```

- also see example.php in the root folder

### Misc:
- idea is that you can have a managed list of cron jobs