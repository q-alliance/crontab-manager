# Q Alliance / Crontab Manager

### Installation:
```bash
$ composer require q-alliance/crontab-manager
```

### Usage:
* create a reader, send username as parameter (use common sense, you cannot set root crontab if you are running this as joeaverage user)
* create a writer and inject the reader 
* run update with array of cron jobs to add

##### for example:

```php
<?php

use QAlliance\CrontabManager\Factory;

$listOfCronjobs = [
    '3 */4 * * * /home/test/dev/bittrex-logger/bin/console bittrex:fetch --verbose',
    '9 */12 * * 0 /home/test/keke/vendor/bin/foobar run --die',
    '11 1 * * 1 /usr/bin/php /var/www/sample.q-software.com/bin/console app:timerweekteamwork',
];

$writer = Factory::createWriter('www-data');
$writer->updateManagedCrontab($cronJobs);
```
* all users current cron jobs will be left intact
* a new `block` of cron jobs will be added to crontab, using the list provided
* this block is managed by this library - if you add or remove jobs from the array, they will be updated when you run the updateMangedCrontab method
* also see [example.php](example.php) in the root folder

### Misc & TODO:
* tests
* create a symfony bundle to integrate this with Symfony 4 framework
* CLI command that can be run via composer install to auto-update your crontabs with every new release
