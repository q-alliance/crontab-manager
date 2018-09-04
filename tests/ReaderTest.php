<?php

namespace Acrnogor\CrontabManager\Tests;

use Acrnogor\CrontabManager\Reader;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;

function shell_exec($command) {
    return '
3 */4 * * * /home/zwer/dev/bittrex-logger/bin/console bittrex:fetch --verbose
9 */12 * * 0 /home/miro/keke/vendor/bin/samsonite run --die
30 9 * * * /usr/bin/php /var/www/integrations.q-software.com/bin/console app:timerteamwork
45 8 * * 1 /usr/bin/php /var/www/integrations.q-software.com/bin/console app:timerweekteamwork
00 8 * * * /usr/bin/php /var/www/integrations.q-software.com/bin/console app:usersync
*/2 * * * * /usr/bin/php /var/www/crawlers.q-tests.com/bin/console q:project:scrape > /dev/null 2>&1
*/5 * * * * /usr/bin/php /var/www/crawlers.q-tests.com/bin/console q:project:scrape-urls > /dev/null 2>&1
*/5 * * * * /usr/bin/php /var/www/crawlers.q-tests.com/bin/console q:project:scrape-urls-check > /dev/null 2>&1

#CTMSTART

*/5 * * * * /usr/bin/php /var/www/test.q-tests.com/bin/console crap:crappity > /dev/null 2>&1

#CTMEND
';
}

class ReaderTest extends TestCase
{
//    public function testReaderCanReadFromCrontab()
//    {
//        $reader = new Reader();
//
//        $this->assertContains('timerteamwork', $reader->getCrontabAsString());
//        $this->assertContains('9 */12 * * 0 /home/miro/keke/vendor/bin/samsonite run --die', $reader->getCrontabAsString());
//        $this->assertContains(
//            '*/5 * * * * /usr/bin/php /var/www/crawlers.q-tests.com/bin/console q:project:scrape-urls-check > /dev/null 2>&1',
//            $reader->getCrontabAsString()
//        );
//    }

    public function testReaderCanGetManagedCronJobsAsString()
    {
        $reader = new Reader();
        $managedCronJobs = $reader->getManagedCronJobsAsString();

        print_r($managedCronJobs); die;

        $this->assertEquals(
            '*/5 * * * * /usr/bin/php /var/www/test.q-tests.com/bin/console crap:crappity > /dev/null 2>&1',
            "\m". $managedCronJobs ."\n"
        );

    }


}