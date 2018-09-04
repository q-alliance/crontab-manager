<?php

namespace QAlliance\CrontabManager;

class Reader
{
    /** Regex used to extract managed crontab block */
    public const MANAGED_CRONTAB_MATCHER = '$\#CTMSTART([\s\S]*)\#CTMEND$';

    /** @var string  */
    private $user;

    /** @var string */
    private $crontab;

    public function __construct($user = null)
    {
        if (!$user) {
            $user = shell_exec('id -u -n');
            $user= trim(preg_replace('/\s+/', ' ', $user));
        }
        $this->user = $user;

        $crontab = shell_exec(sprintf('crontab -l  -u %s', $this->user));
        $this->crontab = $crontab ?? '';
    }

    public function getCrontabAsString(): string
    {
        return $this->crontab;
    }

    public function getManagedCronJobsAsString(): string
    {
        $result = '';

        preg_match(self::MANAGED_CRONTAB_MATCHER, $this->getCrontabAsString(), $matches);

        if (isset($matches[1])) {
            $result = $matches[1];
        }

        return $result;
    }

    public function getManagedCronJobsAsArray(): array
    {
        $results = [];

        preg_match(self::MANAGED_CRONTAB_MATCHER, $this->getCrontabAsString(), $matches);

        if (isset($matches[1])) {
            $matches = $matches[1];
            $results = array_values(array_filter(explode("\n", $matches)));
        }

        return $results;
    }

    public function hasManagedBlock(): bool
    {
        return \count($this->getManagedCronJobsAsArray()) > 0;
    }

    public function getUser()
    {
        return $this->user;
    }

}