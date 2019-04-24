<?php

namespace QAlliance\CrontabManager\CommandLine;

class Crontab
{
    protected const CRONTAB_BIN = '/usr/bin/crontab';

    /**
     * @var string
     */
    private $entries;

    /**
     * @var \QAlliance\CrontabManager\CommandLine\TemporaryFile
     */
    private $tempFile;

    /**
     * Crontab constructor.
     *
     * @param \QAlliance\CrontabManager\CommandLine\TemporaryFile $tempFile
     * @param \QAlliance\CrontabManager\CommandLine\Username $user
     */
    public function __construct(TemporaryFile $tempFile, Username $user)
    {
        $this->user = $user;
        $this->tempFile = $tempFile;
        $this->entries = $this->read();
    }

    public function getEntries(): string
    {
        return (string)$this->entries;
    }

    public function getUser(): Username
    {
        return $this->user;
    }

    public function read(): string
    {
        $crontab = shell_exec(sprintf('%s -l -u %s',self::CRONTAB_BIN, (string)$this->user));

        return  $crontab ?? '';
    }

    public function write(string $payload): void
    {
        file_put_contents($this->tempFile, $payload);

        $writeCommand = sprintf('%s -u %s %s', self::CRONTAB_BIN, (string)$this->user, $this->tempFile);

        shell_exec($writeCommand);
    }
}
