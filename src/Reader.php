<?php

declare(strict_types=1);

namespace QAlliance\CrontabManager;

use Symfony\Component\Process\Process;

/**
 * Reader.
 *
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

    public function __construct(string $user)
    {
        $userString = preg_replace('/\s+/', ' ', $user);
        if (null === $userString) {
            throw new \InvalidArgumentException(sprintf('User not found or invalid user given (%s)', $user));
        }

        $this->user = trim($userString);
        $command = sprintf('crontab -l -u %s', $this->user);
        $process = new Process(explode(' ', $command));
        $process->run();

        $crontab = $process->getOutput();

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

    public function getUser(): string
    {
        return $this->user;
    }
}
