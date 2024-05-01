<?php

namespace App\Command;

use App\Helpers\ApiCommandHelper;
use App\Services\FetchApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

abstract class BaseApiAbstractCommand extends Command
{
    protected $entities ;

    public function __construct(protected FetchApiService $service)
    {
        $this->entities = ApiCommandHelper::getEntities();
        parent::__construct();
    }

    protected function chooseEntity(): Question
    {
        $question = new ChoiceQuestion('Please select type of entity', $this->entities, 0);
        $question->setErrorMessage('Value "%s" is not in the suggested list');
        return $question;
    }

    protected function callSimpleQuestion(string $attribute, string $type = 'string'): Question
    {
        $question = new Question('Please enter the ' . $attribute . ': ');
        if($type === 'int') {
            $question->setValidator(function ($answer): int {
                $val = intval($answer);
                if ($val <= 0) {
                    throw new \RuntimeException('This value should be a positive integer number');
                }
                return $val;
            });
        }
        $question->setMaxAttempts(3);
        return $question;
    }

}