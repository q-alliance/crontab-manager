<?php

declare(strict_types=1);

namespace QAlliance\CrontabManager;

use Symfony\Component\Process\Process;

/**
 * Writer.
 *
 * @author Ante Crnogorac <ante@q-software.com>
 */
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

    public function __construct(Reader $reader, $tempFile = null)
    {
        $this->reader = $reader;

        if (null !== $tempFile) {
            $pathArray = pathinfo($tempFile);
            if (!is_writable($pathArray['dirname'])) {
                $message = sprintf('Unable to write to temporary file or folder (%s), please make sure current user has correct permissions', $tempFile);

                throw new \InvalidArgumentException($message);
            }
            $this->tempFile = $tempFile;
        }
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

        $newCronJobsAsString = PHP_EOL . implode(PHP_EOL, $newCronJobs) . PHP_EOL;

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

    private function writeToCrontab($crontab): bool
    {
        file_put_contents($this->tempFile, $crontab);
        $writeCommand = sprintf('/usr/bin/crontab -u %s %s', $this->reader->getUser(), $this->tempFile);
        $process = new Process(explode(' ', $writeCommand));

        $process->run();

        return $process->isSuccessful();
    }
}
