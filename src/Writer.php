<?php

namespace QAlliance\CrontabManager;

use QAlliance\CrontabManager\CommandLine\Crontab;
use function implode;
use function str_replace;

/**
 * Writer
 *
 * @package QAlliance\CrontabManager
 * @author Ante Crnogorac <ante@q-software.com>
 */
class Writer extends CrontabAware
{
    public const PLACEHOLDER_STRING = '{PLACEHOLDER}';

    /**
     * @var \QAlliance\CrontabManager\Reader
     */
    private $reader;

    private $template = '
#CTMSTART
{PLACEHOLDER}
#CTMEND
';

    /**
     * Writer constructor.
     *
     * @param \QAlliance\CrontabManager\Reader $reader
     * @param \QAlliance\CrontabManager\CommandLine\Crontab $crontab
     */
    public function __construct(Reader $reader, Crontab $crontab)
    {
        parent::__construct($crontab);
        $this->reader = $reader;
    }

    public function updateManagedCrontab(array $newCronJobs): void
    {
        $crontab = $this->reader->getCrontabAsString();

        if ($this->reader->hasManagedBlock()) {
            $managedCrontabJobs = $this->reader->getManagedCronJobsAsString();
            $crontab = $this->removeManagedCronJobs($crontab, $managedCrontabJobs);
        } else {
            $crontab .= $this->template;
        }

        $newCronJobsAsString = PHP_EOL. implode(PHP_EOL, $newCronJobs) . PHP_EOL;

        $crontab = str_replace(
            self::PLACEHOLDER_STRING,
            $newCronJobsAsString,
            $crontab
        );

        $this->writeToCrontab($crontab);
    }

    protected function removeManagedCronJobs($crontab, $managedCrontabJobs): string
    {
        return str_replace(
            $managedCrontabJobs,
            self::PLACEHOLDER_STRING,
            $crontab
        );
    }

    private function writeToCrontab($crontab): void
    {
        $this->crontab->write($crontab);
    }
}
