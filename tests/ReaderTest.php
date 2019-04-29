<?php

namespace QAlliance\CrontabManager\Tests;

use PHPUnit\Framework\TestCase;
use QAlliance\CrontabManager\CommandLine\Crontab;
use QAlliance\CrontabManager\CommandLine\Username;
use QAlliance\CrontabManager\Reader;

class ReaderTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $crontab;

    /**
     * @var \QAlliance\CrontabManager\Reader
     */
    protected $reader;

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
    protected $entries = '
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

    public function setUp()
    {
        $this->crontab = $this->getMockBuilder(Crontab::class)
            ->disableOriginalConstructor()
            ->setMethods(['getEntries', 'getUser', 'read', 'write'])
            ->getMock();
        $this->reader = new Reader($this->crontab);
    }

    public function testReaderReturnsEntriesProvidedByCron(): void
    {
        $this->crontab->expects($this->once())
            ->method('getEntries')
            ->willReturn($this->rawEntries);
        $this->assertEquals($this->rawEntries, $this->reader->getCrontabAsString());
    }

    public function testReaderReturnsValidManagedCronJobs(): void
    {
        $this->crontab->expects($this->once())
            ->method('getEntries')
            ->willReturn($this->rawEntries);
        $this->assertEquals($this->entries, $this->reader->getManagedCronJobsAsString());
    }

    public function testReaderReturnsValidManagedCronJobsAsArray(): void
    {
        $this->crontab->expects($this->once())
            ->method('getEntries')
            ->willReturn($this->rawEntries);
        $this->assertEquals($this->entriesFiltered, $this->reader->getManagedCronJobsAsArray());
    }

    public function testReaderReturnsValidCount(): void
    {
        $this->crontab->expects($this->once())
            ->method('getEntries')
            ->willReturn($this->rawEntries);
        $this->assertTrue($this->reader->hasManagedBlock());
    }

    public function testReaderReturnsValidCountWithoutDelimiters(): void
    {
        $this->crontab->expects($this->once())
            ->method('getEntries')
            ->willReturn($this->entries);
        $this->assertFalse($this->reader->hasManagedBlock());
    }

    public function testReaderReturnsValidUsername(): void
    {
        $username = new Username('www-data');
        $this->crontab->expects($this->once())
            ->method('getUser')
            ->willReturn($username);
        $this->assertEquals((string)$username, $this->reader->getUser());
    }
}