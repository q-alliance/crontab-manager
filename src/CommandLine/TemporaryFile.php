<?php
namespace QAlliance\CrontabManager\CommandLine;

use InvalidArgumentException;
use function pathinfo;
use function is_writable;
use function sprintf;

/**
 * Class TemporaryFile
 * @package QAlliance\CrontabManager\CommandLine
 * @author Mario Blazek <mario.b@netgen.hr>
 */
class TemporaryFile
{
    /**
     * @var string
     */
    private $file = '/tmp/ctm_temp.xxx';

    public function __construct($file = null)
    {
        if (null !== $file) {
            $pathArray = pathinfo($file);
            if (!is_writable($pathArray['dirname'])) {
                $message = sprintf('Unable to write to temporary file or folder (%s), please make sure current user has correct permissions', $file);
                throw new InvalidArgumentException($message);
            }
            $this->file = $file;
        }
    }

    public function __toString(): string
    {
        return (string) $this->file;
    }
}