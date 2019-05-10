<?php

namespace QAlliance\CrontabManager\CommandLine;

use Symfony\Component\Process\Process;

class Crontab
{
    protected const CRONTAB_BIN = '/usr/bin/crontab';

    private $entries;
    private $tempFile;
    private $user;

    /**
     * Crontab constructor.
     *
     * @param TemporaryFile $tempFile
     * @param Username $user
     */
    public function __construct(TemporaryFile $tempFile, Username $user)
    {
        $this->user = $user;
        $this->tempFile = $tempFile;
        $this->entries = $this->read();
    }

    public function getEntries(): string
    {
        return $this->entries;
    }

    public function getUser(): Username
    {
        return $this->user;
    }

    public function read(): string
    {
        $command = sprintf('crontab -l -u %s', $this->getUser());
        $process = new Process(explode(' ', $command));
        $process->run();
        $crontab = $process->getOutput();
        if (!$process->isSuccessful() && $process->getErrorOutput() !== "no crontab for www-data\n") {
            throw new \InvalidArgumentException('Unable to read crontab entries - invalid user or insufficient permissions?');
        }

        return  $crontab ?? '';
    }

    public function write(string $payload): bool
    {
        file_put_contents($this->tempFile, $payload);

        $writeCommand = sprintf(
            '%s -u %s %s',
            self::CRONTAB_BIN,
            (string) $this->getUser(),
            (string) $this->tempFile
        );

        $process = new Process(explode(' ', $writeCommand));
        $process->run();

        return $process->isSuccessful();
    }
}