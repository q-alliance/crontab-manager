<?php
namespace QAlliance\CrontabManager;

use QAlliance\CrontabManager\CommandLine\Crontab;
use QAlliance\CrontabManager\CommandLine\TemporaryFile;
use QAlliance\CrontabManager\CommandLine\Username;

final class Factory
{
    public static function createWriter(string $username, $tempFile = null): Writer
    {
        $user       = new Username($username);
        $tempFile   = new TemporaryFile($tempFile);
        $crontab    = new Crontab($tempFile, $user);
        $reader     = new Reader($crontab);

        return new Writer($reader, $crontab);
    }
}