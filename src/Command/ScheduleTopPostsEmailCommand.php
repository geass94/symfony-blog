<?php

namespace App\Command;

use App\Mail\SendTopPostsEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:schedule-top-posts-email',
    description: 'Dispatch the weekly top posts email job',
)]
class ScheduleTopPostsEmailCommand extends Command
{
    public function __construct(
        private MessageBusInterface $bus
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bus->dispatch(new SendTopPostsEmail());

        $output->writeln('Top posts email job dispatched.');
        return Command::SUCCESS;
    }
}
