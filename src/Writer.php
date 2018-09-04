<?php

namespace QAlliance\CrontabManager;

class Writer
{
    public const PLACEHOLDER_STRING = '{PLACEHOLDER}';

    /** @var Reader */
    private $reader;

    /** @var string */
    private $tempFile = '/tmp/ctm_temp.xxx';

    private $template = '
#CTMSTART
{PLACEHOLDER}
#CTMEND
';

    public function __construct(Reader $reader, string $user = null)
    {
        $this->reader = $reader;
    }


    public function updateManagedCrontab(array $newCronJobs)
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

    private function writeToCrontab($crontab)
    {
        file_put_contents($this->tempFile, $crontab);
        $writeCommand = sprintf('/usr/bin/crontab -u %s %s', $this->reader->getUser(), $this->tempFile);
        shell_exec($writeCommand);
    }
}