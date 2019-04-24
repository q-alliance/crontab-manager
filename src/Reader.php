<?php

namespace QAlliance\CrontabManager;

use function array_values;
use function array_filter;
use function count;
use function explode;
use function preg_match;

/**
 * Reader
 *
 * @package QAlliance\CrontabManager
 * @author Ante Crnogorac <ante@q-software.com>
 */
class Reader extends CrontabAware
{
    /** Regex used to extract managed crontab block */
    public const MANAGED_CRONTAB_MATCHER = '$\#CTMSTART([\s\S]*)\#CTMEND$';

    public function getCrontabAsString(): string
    {
        return $this->crontab->getEntries();
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
        return count($this->getManagedCronJobsAsArray()) > 0;
    }

    public function getUser(): string
    {
        return (string)$this->crontab->getUser();
    }
}
