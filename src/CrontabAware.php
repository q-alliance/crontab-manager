<?php

namespace QAlliance\CrontabManager;

use QAlliance\CrontabManager\CommandLine\Crontab;

abstract class CrontabAware
{
    /**
     * @var \QAlliance\CrontabManager\CommandLine\Crontab
     */
    protected $crontab;

    /**
     * CrontabAware constructor.
     *
     * @param \QAlliance\CrontabManager\CommandLine\Crontab $crontab
     */
    public function __construct(Crontab $crontab)
    {
        $this->crontab = $crontab;
    }
}
