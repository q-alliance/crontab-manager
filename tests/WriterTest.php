<?php
namespace QAlliance\CrontabManager\Tests;

use PHPUnit\Framework\TestCase;
use QAlliance\CrontabManager\CommandLine\Crontab;
use QAlliance\CrontabManager\Reader;
use QAlliance\CrontabManager\Writer;

class WriterTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $reader;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $crontab;

    /**
     * @var \QAlliance\CrontabManager\Writer
     */
    protected $writer;

    /**
     * @var string
     */
    protected $rawEntries = '
#CTMSTART
3 */4 * * * /home/test/dev/bittrex-logger/bin/console bittrex:fetch --verbose --test
9 */12 * * 0 /home/test/keke/vendor/bin/foobar run --die
11 1 * * 1 /usr/bin/php /var/www/sample.q-software.com/bin/console list app
#CTMEND
';

    /**
     * @var string
     */
    protected $rawEntries2 = '
#CTMSTART
3 */4 * * * /home/test/dev/bittrex-logger/bin/console bittrex:fetch --verbose --test
9 */12 * * 0 /home/test/keke/vendor/bin/foobar run --die
11 1 * * 1 /usr/bin/php /var/www/sample.q-software.com/bin/console list app
#CTMEND
';

    /**
     * @var string
     */
    protected $rawEntries2input = '
3 */4 * * * /home/test/dev/bittrex-logger/bin/console bittrex:fetch --verbose --test
9 */12 * * 0 /home/test/keke/vendor/bin/foobar run --die
11 1 * * 1 /usr/bin/php /var/www/sample.q-software.com/bin/console list app
';

    /**
     * @var string
     */
    protected $rawEntries2end = '
#CTMSTART
9 */12 * * 0 /home/test/testis/vendor/bin/foobar run --die
11 1 * * 1 /usr/bin/php5.6 /var/www/sample.q-software.com/bin/console list app
#CTMEND
';

    /**
     * @var string
     */
    protected $entries = '
3 */4 * * * /home/test/dev/bittrex-logger/bin/console bittrex:fetch --verbose --test
3 */4 * * * /home/test/dev/bittrex-logger/bin/console bittrex:fetch --verbose --test
9 */12 * * 0 /home/test/keke/vendor/bin/foobar run --die
11 1 * * 1 /usr/bin/php /var/www/sample.q-software.com/bin/console list app
';

    /**
     * @var array
     */
    protected $entriesFiltered = [
        '3 */4 * * * /home/test/dev/bittrex-logger/bin/console bittrex:fetch --verbose --test',
        '9 */12 * * 0 /home/test/keke/vendor/bin/foobar run --die',
        '11 1 * * 1 /usr/bin/php /var/www/sample.q-software.com/bin/console list app',
    ];

    protected $entriesFiltered2 = [
        '9 */12 * * 0 /home/test/testis/vendor/bin/foobar run --die',
        '11 1 * * 1 /usr/bin/php5.6 /var/www/sample.q-software.com/bin/console list app',
    ];

    public function setUp()
    {
        $this->crontab = $this->getMockBuilder(Crontab::class)
            ->disableOriginalConstructor()
            ->setMethods(['getEntries', 'getUser', 'read', 'write'])
            ->getMock();
        $this->reader = $this->getMockBuilder(Reader::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCrontabAsString', 'getManagedCronJobsAsString', 'getManagedCronJobsAsArray', 'hasManagedBlock', 'getUser'])
            ->getMock();
        $this->writer = new Writer($this->reader, $this->crontab);
    }

    public function testItProperlyUpdatesCrontab(): void
    {
        $this->reader->expects($this->once())
            ->method('getCrontabAsString')
            ->willReturn('');
        $this->reader->expects($this->once())
            ->method('hasManagedBlock')
            ->willReturn(false);

        $this->crontab->expects($this->once())
            ->method('write')
            ->willReturn(true);

        $this->writer->updateManagedCrontab($this->entriesFiltered);
    }

    public function testItProperlyUpdatesCrontabWhenIsNotEmpty(): void
    {
        $this->reader->expects($this->once())
            ->method('getCrontabAsString')
            ->willReturn($this->rawEntries);
        $this->reader->expects($this->once())
            ->method('hasManagedBlock')
            ->willReturn(true);
        $this->reader->expects($this->once())
            ->method('getManagedCronJobsAsString')
            ->willReturn($this->rawEntries2input);
        $this->crontab->expects($this->once())
            ->method('write')
            ->with($this->rawEntries2end);
        $this->writer->updateManagedCrontab($this->entriesFiltered2);
    }
}