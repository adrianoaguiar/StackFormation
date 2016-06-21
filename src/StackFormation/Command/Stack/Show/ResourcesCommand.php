<?php

namespace StackFormation\Command\Stack\Show;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResourcesCommand extends \StackFormation\Command\AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName('stack:show:resources')
            ->setDescription('Show a live stack\'s resources')
            ->addArgument(
                'stack',
                InputArgument::REQUIRED,
                'Stack'
            )
            ->addArgument(
                'key',
                InputArgument::OPTIONAL,
                'key'
            );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->interactAskForStack($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stack = $this->stackFactory->getStack($input->getArgument('stack'));

        $key = $input->getArgument('key');
        if ($key) {
            $output->writeln($stack->getResource($key));
            return;
        }

        $data = $stack->getResources();

        $rows = [];
        foreach ($data as $k => $v) {
            $v = strlen($v) > 100 ? substr($v, 0, 100) . "..." : $v;
            $rows[] = [$k, $v];
        }

        $table = new Table($output);
        $table->setHeaders(['Key', 'Value'])
            ->setRows($rows)
            ->render();
    }
}
