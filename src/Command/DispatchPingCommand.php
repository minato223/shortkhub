<?php

namespace App\Command;

use App\Infrastructure\Mercure\MercureService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'mino:ping')]
class DispatchPingCommand extends Command
{
    protected static $defaultDescription = 'Envoie d\'un ping mercure';
    public function __construct(
        readonly private MercureService $mercureService
    ) {
        parent::__construct();
    }
    function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->mercureService->sendMessage("Ping !!!", "/progress");
        $output->writeln([
            "Ping !!!",
        ]);
        return Command::SUCCESS;
    }

    function configure()
    {
        $this
            ->setHelp('Cette commande permet de diffuser un ping via mercure...');
    }
}