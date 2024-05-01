<?php

namespace App\Command;

use App\Helpers\ApiCommandHelper;
use App\Services\Builders\PutRequestBuilder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'put-request',
    description: 'Make API-request to fully update a single record (all non-nullable fields required)',
    aliases: ['api:put-request']
)]
class PutRequestCommand extends BaseApiAbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $requestBuilder = new PutRequestBuilder();

        $helper = $this->getHelper('question');

        $questionEntity = $this->chooseEntity();
        $entity = $helper->ask($input, $output, $questionEntity);

        $requestBuilder->buildEntity($entity);

        // ask user for record's id
        $q = $this->callSimpleQuestion('id', 'int');
        $key = $helper->ask($input, $output, $q);
        $requestBuilder->buildPrimaryKey($key);

        // as user for filling the fields
        $fields = ApiCommandHelper::mapEntityFields($entity);
        $inputData = [];
        foreach ($fields as $field) {
            $question = $this->callSimpleQuestion($field);
            $fieldValue = $helper->ask($input, $output, $question);
            $inputData[$field] = $fieldValue;
        }

        $requestBuilder->buildBody($inputData);

        $request = $requestBuilder->getRequest();

        $this->service->setApiRequest($request);

        $data = $this->service->sendRequest();

        print_r($data);

        return Command::SUCCESS;
    }
}
