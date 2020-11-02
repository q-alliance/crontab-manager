<?php

declare(strict_types=1);

namespace QAlliance\CrontabManager;

use function array_values;
use function array_filter;
use function count;
use function explode;
use function preg_match;


/**
 * Reader.
 *
 * @author Ante Crnogorac <ante@q-software.com>
 * @author Mario Blazek <mario.b@netgen.hr>
 */
class Reader extends CrontabAware
{

    public function getCrontabAsString(): string
    {
        return $this->crontab->getEntries();
    }

    public function getMatches(): array
    {
        $matches = [];
        if (preg_match($this->getCrontabMatcher(), $this->getCrontabAsString(), $matches)) {
            return $matches;
        } else {
            /** backward compatibility */
            $unique = str_replace('\\','\\\\',PHP_EOL);
            preg_match('$\#CTMSTART'.$unique.'([\s\S]*)\#CTMEND'.$unique.'$', $this->getCrontabAsString(), $matches);
        }

        return $matches;
    }

    public function getManagedCronJobsAsString(): string
    {
        $result = '';

        $matches = $this->getMatches();
        if (isset($matches[1])) {
            $result = $matches[1];
        }

        return $result;
    }

    public function getManagedCronJobsAsArray(): array
    {
        $results = [];

        $matches = $this->getMatches();
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
        return (string) $this->crontab->getUser();
    }
}
