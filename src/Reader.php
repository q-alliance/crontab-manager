<?php

namespace QAlliance\CrontabManager;

/**
 * Reader
 *
 * @package QAlliance\CrontabManager
 * @author Ante Crnogorac <ante@q-software.com>
 */
class Reader
{
    /** Regex used to extract managed crontab block */
    public const MANAGED_CRONTAB_MATCHER = '$\#CTMSTART([\s\S]*)\#CTMEND$';

    /** @var string */
    private $user;

    /** @var string */
    private $crontab;

    public function __construct(string $user = null)
    {
        if (!$user) {
            $user = (string) shell_exec('id -u -n');
        }

        $this->user = trim(preg_replace('/\s+/', ' ', $user));

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