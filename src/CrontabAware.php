<?php

namespace QAlliance\CrontabManager;
use QAlliance\CrontabManager\CommandLine\Crontab;

/**
 * Class CrontabAware
 * @package QAlliance\CrontabManager
 * @author Mario Blazek <mario.b@netgen.hr>
 */
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

    /**
     * Unique project path.
     */
    protected function getVendorPath()
    {
        return dirname(dirname(__DIR__));
    }

    /**
     * Regex used to extract managed crontab block.
     */
    protected function getCrontabMatcher()
    {
        return '$\#CTMSTART '.$this->getVendorPath().'([\s\S]*)\#CTMEND$';
    }
}
