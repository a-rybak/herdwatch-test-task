<?php

namespace App\Command;

use App\Helpers\ApiCommandHelper;
use App\Services\Builders\PostRequestBuilder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'post-request',
    description: 'Make API-request to create single record',
    aliases: ['api:post-request']
)]
class PostRequestCommand extends BaseApiAbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $requestBuilder = new PostRequestBuilder();

        $helper = $this->getHelper('question');

        $questionEntity = $this->chooseEntity();
        $entity = $helper->ask($input, $output, $questionEntity);

        $requestBuilder->buildEntity($entity);

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
