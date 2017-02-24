<?php

namespace BerryGoudswaard\Command;

use GuzzleHttp\ClientInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

class BaseCommand extends Command
{
    protected $client;
    protected $logger;
    
    public function __construct(ClientInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->logger = new ConsoleLogger($output, [
            LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::INFO   => OutputInterface::VERBOSITY_NORMAL,
        ]);

        parent::initialize($input, $output);
    }
}
