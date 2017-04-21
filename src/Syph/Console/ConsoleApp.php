<?php
/**
 * Created by PhpStorm.
 * User: Bruno Louvem
 * Date: 17/03/2016
 * Time: 13:35
 */

namespace Syph\Console;



use Syph\Console\Commands\Command;
use Syph\Console\Commands\CommandInterface;
use Syph\Console\Input\InputInterface;
use Syph\Core\Interfaces\SyphKernelInterface;

class ConsoleApp
{
    private $kernel;
    private $commands = array();

    const CONSOLE_DIR = __DIR__;
    /**
     * ConsoleApp constructor.
     */
    public function __construct(SyphKernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->loadNativeCommands();
    }

    public function loadNativeCommands()
    {
        foreach ($this->kernel->getNativeCommands() as $command) {
            $reflect = new \ReflectionClass(Command::_namespace.'\\NativeCommands\\'.$command);
            $this->registerCommand($reflect->newInstance($command,0));
        }

    }

    public function register(InputInterface $input)
    {
        $command = $input->getCommand();
        $this->registerCommand($command);
    }

    public function registerCommand(CommandInterface $command)
    {
        if(!$this->hasCommand($command->getName()))
            $this->addCommand($command);
    }

    public function run()
    {
        /**
         * @var CommandInterface $command
         */
        foreach ($this->commands as $command) {
            $command->run();
        }

    }

    public function addCommand(CommandInterface $command)
    {
        $this->commands[$command->getName()] = $command;
    }

    public function hasCommand($command_name)
    {
        return array_key_exists($command_name, $this->commands);
    }
}