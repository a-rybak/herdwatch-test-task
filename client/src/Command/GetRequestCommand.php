<?php

namespace App\Command;

use App\Services\Builders\GetRequestBuilder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

#[AsCommand(
    name: 'get-request',
    description: 'Make API-request to get single record or list of records',
    aliases: ['api:get-request']
)]
class GetRequestCommand extends BaseApiAbstractCommand
{
    protected function configure(): void
    {
        $this->addOption(
            'limit',
            null,
            InputOption::VALUE_OPTIONAL,
            'Limit for fetching records',
            10
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $requestBuilder = new GetRequestBuilder();

        $helper = $this->getHelper('question');

        $questionEntity = $this->chooseEntity();
        $entity = $helper->ask($input, $output, $questionEntity);

        $requestBuilder->buildEntity($entity);

        $questionId = new ChoiceQuestion(
            'Do you want to retrieve a single record? (defaults to [no])',
            [
                'y' => 'Yes, by ID',
                'n' => 'No, get all'
            ],
            'n'
        );
        $idAnswer = $helper->ask($input, $output, $questionId);
        if ($idAnswer === 'y') {
            $q = $this->callSimpleQuestion('id', 'int');
            $key = $helper->ask($input, $output, $q);
            $requestBuilder->buildPrimaryKey($key);
        }

        if ($input->getOption('limit')) {
            $limit = (int)$input->getOption('limit');
            $requestBuilder->buildLimit($limit);
        }

        $request = $requestBuilder->getRequest();

        $this->service->setApiRequest($request);

        $data = $this->service->sendRequest();

        print_r($data);

        return Command::SUCCESS;
    }

}
