<?php

namespace QAlliance\CrontabManager;

class Writer
{
    public const PLACEHOLDER_STRING = '{PLACEHOLDER}';

    /** @var Reader */
    private $reader;

    private $template = '
#CTMSTART
{PLACEHOLDER}
#CTMEND
';

    public function __construct(Reader $reader)
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

        file_put_contents('/tmp/ctm_temp.xxx', $crontab);
        echo shell_exec('/usr/bin/crontab /tmp/ctm_temp.xxx');
    }

    protected function removeManagedCronJobs($crontab, $managedCrontabJobs): string
    {
        return str_replace(
            $managedCrontabJobs,
            self::PLACEHOLDER_STRING,
            $crontab
        );
    }
}