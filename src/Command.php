<?php declare(strict_types=1);

namespace Jioolk\SymfonyCompatibility;

use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends \Symfony\Component\Console\Command\Command implements SignalableCommandInterface
{
    /**
     * @var InputInterface
     */
    protected $in;

    /**
     * @var OutputInterface
     */
    protected $out;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected abstract function doExecute( InputInterface $input, OutputInterface $output );

    /**
     * @param int $signal
     * @return int|false
     *
     * @throws \Exception
     */
    protected abstract function doHandleSignal( int $signal );

    /**
     * @return int[]
     */
    public function getSubscribedSignals(): array
    {
        return [];
    }

    /**
     * @param int $signal
     * @return int|false
     *
     * @throws \Exception
     * @inheritDoc
     * @internal
     */
    public function handleSignal( int $signal, int|false $previousExitCode = 0 ): int|false
    {
        return $this->doHandleSignal($signal);
    }

    /**
     * Performs command itself
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     *
     * @internal
     */
    protected final function execute( InputInterface $input, OutputInterface $output ): int
    {
        $this->in  = $input;
        $this->out = $output;

        return $this->doExecute($input, $output);
    }
}