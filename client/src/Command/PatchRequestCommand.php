<?php

namespace App\Command;

use App\Helpers\ApiCommandHelper;
use App\Services\Builders\PatchRequestBuilder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'patch-request',
    description: 'Make API-request to update some fields of a record',
    aliases: ['api:patch-request']
)]
class PatchRequestCommand extends BaseApiAbstractCommand
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $requestBuilder = new PatchRequestBuilder();

        $helper = $this->getHelper('question');

        $questionEntity = $this->chooseEntity();
        $entity = $helper->ask($input, $output, $questionEntity);

        $requestBuilder->buildEntity($entity);

        // ask user for record's id
        $q = $this->callSimpleQuestion('id', 'int');
        $key = $helper->ask($input, $output, $q);
        $requestBuilder->buildPrimaryKey($key);

        // get all available fields for selected entity
        $fields = ApiCommandHelper::mapEntityFields($entity);
        $io->success('Here are the available fields to edit for the entity "'.$entity.'"' . PHP_EOL . implode(" ", $fields));

        // ask user for them
        $question = new Question("Type all fieldnames or some of them using whitespace...".PHP_EOL);
        $userInput = $helper->ask($input, $output, $question);

        // invite to fill them to send in our request then
        $inputData = [];
        $inputedFieldsForUpdate = explode(" ", $userInput);
        foreach ($inputedFieldsForUpdate as $field) {
            if (!in_array($field, $fields)) { // if there was a type - warn and continue
                $io->warning("The \"$field\" is missing in Entity's field list (maybe it was a typo). Try next time...");
                continue;
            }
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
