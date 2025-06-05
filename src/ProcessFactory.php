<?php declare(strict_types=1);

namespace Jioolk\SymfonyCompatibility;

use Symfony\Component\Process\Process;

class ProcessFactory
{
    /**
     * Cache for TTY support flag
     *
     * @var boolean|null
     */
    private static $IS_TTY_SUPPORTED = null;

    /**
     * Creates process with given command as parameter
     *
     * @param string|mixed[] $command
     * @return Process
     */
    public static function createProcess( string|array $command, bool $tty = false ): Process
    {
        if ( \is_string($command) ) {
            $p = Process::fromShellCommandline($command);
        } else {
            $p = new Process($command);
        }

        if ( self::$IS_TTY_SUPPORTED === null ) {
            self::$IS_TTY_SUPPORTED = Process::isTtySupported();
        }

        if ( $tty === true ) {
            $p->setTty(self::$IS_TTY_SUPPORTED);
        }
        return $p;
    }

    /**
     * Typical process via symfony is created as two PIDs, one sh -c 'real command' and its child of 'real command'
     * That's quite a bit of struggle that exists just for param escaping -> and is not needed/applied in case we just pass it all in array as separate items.
     *
     * We are very really sure we can explode any commonscript run target command safely, so we use it like this.
     *
     * @param string|mixed[] $command
     * @param bool $tty
     * @return Process
     *
     * @throws \Exception
     */
    public static function createProcessNonEscaping( string|array $command, bool $tty = false ): Process
    {
        if ( \is_array($command) ) {
            return self::createProcess($command, $tty);
        }

        $command        = \trim($command);
        $trimmedCommand = \preg_replace('/\s+/', ' ', $command);
        if ( $trimmedCommand === null ) {
            throw new \Exception('Failed to create process for: ' . \substr($command, 0, 254));
        }

        $pieces = \explode(' ', $trimmedCommand);
        foreach ( $pieces as &$piece ) {
            $piece = \trim($piece);
        }

        return self::createProcess($pieces, $tty);
    }
}
