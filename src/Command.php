<?php declare(strict_types=1);

namespace Jioolk\SymfonyCompatibility;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends \Symfony\Component\Console\Command\Command
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
     * @return void
     *
     * @throws \Exception
     * @internal
     */
    public function handleSignal( int $signal )
    {
        throw new \Exception('Signals are not supported via this mechanism, this was introduced in symfony/console:>5');
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