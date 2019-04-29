<?php
namespace QAlliance\CrontabManager\CommandLine;

/**
 * Class Username
 *
 * @package QAlliance\CrontabManager\CommandLine
 * @author Mario Blazek <mario.b@netgen.hr>
 * @author Ante Crnogorac <zwer82@gmail.com>
 */
class Username
{
    private $user;

    public function __construct(string $user)
    {
        $user = preg_replace('/\s+/', ' ', $user);
        $this->user = trim((string) $user);
    }

    public function __toString()
    {
        return (string) $this->user;
    }
}