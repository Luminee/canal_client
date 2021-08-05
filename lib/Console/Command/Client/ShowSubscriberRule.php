<?php

namespace Lib\Console\Command\Client;

use Lib\Console\Command;
use Lib\Core\Subscriber;

class ShowSubscriberRule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'show:subscriber:rule {subscribers?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show subscriber\'s rule which registered in client.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $subscribers = $this->getSubscribers();

        /**
         * @var Subscriber $subscriber
         */
        foreach ($subscribers as $name => $subscriber) {
            $this->comment('< ' . $name . ' >');
            $this->line(' ');
            foreach ($subscriber->showRule() as $key => $rule) {
                $this->newTable(['Key', 'Value'], $key);
                foreach ($rule as $k => $v) {
                    if (is_array($v)) {
                        $v = $k == 'append' ? $this->buildAppend($v) : implode(', ', $v);
                    }
                    $this->addRow([$k, $v]);
                }
                $this->setColumnWidths([20, 30])->render();
            }
        }
    }

    protected function buildAppend($array)
    {
        $output = '';
        foreach ($array as $k => $value) {
            if ($value instanceof \Closure) $output .= $k . ' => Closure, ';
        }
        return rtrim($output, ', ');
    }

    protected function getSubscribers()
    {
        $subs = $this->argument('subscribers');
        if (empty($subs))
            return $this->app->getSubscribers();
        $subscribers = [];
        foreach (explode(',', $subs) as $sub) {
            $subscribers[$sub] = $this->app->subscribe($sub);
        }
        return $subscribers;
    }

}