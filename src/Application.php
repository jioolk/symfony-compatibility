<?php declare(strict_types=1);

namespace Jioolk\SymfonyCompatibility;

use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends \Symfony\Component\Console\Application
{
    /**
     * We need to modify standard rendering of Symfony throwable otherwise these will spam stdout with big red boxes of errors, which are just misses in commands between project/cs/vendor specifics and don't mean anything just annoy users ( and confuse beginners )
     *
     * --help is used as it is the way cs lookups commands on different paths, trying to find their helps means they exist.
     * One downside - trying to see help of not existing command is blank screen, I can live with that.
     *
     * @param \Throwable $e
     * @param OutputInterface $output
     */
    public function renderThrowable( \Throwable $e, OutputInterface $output ): void
    {
        if ( $e instanceof CommandNotFoundException ) {
            if ( ! \in_array('--help', $_SERVER['argv']) ) {
                $this->doRenderThrowable($e, $output);
                parent::renderThrowable($e, $output);
            }

            return;
        }

        $this->doRenderThrowable($e, $output);
        parent::renderThrowable($e, $output);
    }
}
