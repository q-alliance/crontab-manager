<?php

namespace QAlliance\CrontabManager\CommandLine;

class Username
{
    /**
     * @var string
     */
    private $user;

    public function __construct(string $user = null)
    {
        if (!$user) {
            $user = (string) shell_exec('id -u -n');
        }

        $this->user = trim(preg_replace('/\s+/', ' ', $user));
    }

    public function __toString()
    {
        return (string)$this->user;
    }
}
