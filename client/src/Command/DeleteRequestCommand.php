<?php

namespace App\Command;

use App\Services\Builders\DeleteRequestBuilder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'delete-request',
    description: 'Make API-request to delete single record',
    aliases: ['api:delete-request']
)]
class DeleteRequestCommand extends BaseApiAbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $requestBuilder = new DeleteRequestBuilder();

        $helper = $this->getHelper('question');

        $questionEntity = $this->chooseEntity();
        $entity = $helper->ask($input, $output, $questionEntity);

        $requestBuilder->buildEntity($entity);

        // ask user for record's id
        $q = $this->callSimpleQuestion('id', 'int');
        $key = $helper->ask($input, $output, $q);
        $requestBuilder->buildPrimaryKey($key);

        $request = $requestBuilder->getRequest();

        $this->service->setApiRequest($request);

        $data = $this->service->sendRequest();

        print_r($data);

        return Command::SUCCESS;
    }
}