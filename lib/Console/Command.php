<?php

namespace Lib\Console;

use Log;
use Exception;
use Lib\Core\Application;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as ConsoleCommand;

abstract class Command extends ConsoleCommand
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var Table
     */
    protected $table;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->app = app();

        list($name, $arguments, $options) = Parser::parse($this->signature);

        parent::__construct($name);

        // After parsing the signature we will spin through the arguments and options
        // and set them on this command. These will already be changed into proper
        // instances of these "InputArgument" and "InputOption" Symfony classes.
        foreach ($arguments as $argument) {
            $this->getDefinition()->addArgument($argument);
        }

        foreach ($options as $option) {
            $this->getDefinition()->addOption($option);
        }
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    abstract public function handle();

    protected function configure()
    {

        $this->setDescription($this->description);
        // ...

        // the full command description shown when running the command with
        // the "--help" option
//            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        try {
            $this->handle();
        } catch (Exception $e) {
            Log::error($e);
            $this->error($e->getMessage());
        }
    }

    /**
     * Get the value of a command argument.
     *
     * @param string|null $key
     * @return string|array
     */
    public function argument($key = null)
    {
        if (is_null($key)) {
            return $this->input->getArguments();
        }

        return $this->input->getArgument($key);
    }

    /**
     * Write a string as standard output.
     *
     * @param string $string
     * @param string $style
     * @return void
     */
    public function line($string, $style = null)
    {
        $styled = $style ? "<$style>$string</$style>" : $string;

        $this->output->writeln($styled);
    }

    /**
     * Write a string as information output.
     *
     * @param string $string
     * @return void
     */
    public function info($string)
    {
        $this->line($string, 'info');
    }

    /**
     * Write a string as comment output.
     *
     * @param string $string
     * @return void
     */
    public function comment($string)
    {
        $this->line($string, 'comment');
    }

    /**
     * Write a string as question output.
     *
     * @param string $string
     * @return void
     */
    public function question($string)
    {
        $this->line($string, 'question');
    }

    /**
     * Write a string as error output.
     *
     * @param string $string
     * @return void
     */
    public function error($string)
    {
        $this->line($string, 'error');
    }

    /**
     * @param array $headers
     * @param null $title
     * @return $this
     */
    public function newTable($headers, $title = null)
    {
        $this->table = new Table($this->output);
        $this->table->setHeaderTitle($title)->setHeaders($headers);
        return $this;
    }

    /**
     * @param array $rows
     * @return $this
     */
    public function setRows($rows)
    {
        $this->table->setRows($rows);
        return $this;
    }

    /**
     * @param array $row
     * @return $this
     */
    public function addRow($row)
    {
        $this->table->addRow($row);
        return $this;
    }

    /**
     * @param array $array
     * @return $this
     */
    public function setColumnWidths($array)
    {
        $this->table->setColumnWidths($array);
        return $this;
    }

    /**
     * @return void
     */
    public function render()
    {
        $this->table->render();
    }
}