<?php declare(strict_types=1);

namespace App\Validation;

use HSkrasek\JSONSchema\Validator;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use League\JsonGuard\ValidationError;

class JsonSchemaValidator implements ValidatorContract
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var MessageBag
     */
    private $messages;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function fails()
    {
        return $this->validator->fails();
    }

    public function failed()
    {
        return $this->validator->errors();
    }

    public function sometimes($attribute, $rules, callable $callback)
    {
        // TODO: Implement sometimes() method.
    }

    public function after($callback)
    {
        // TODO: Implement after() method.
    }

    public function errors()
    {
        $errors = collect($this->validator->errors())->mapToGroups(function (ValidationError $error) {
            return [$this->convertDataPathToAttribute($error->getDataPath()) => $error->getMessage()];
        });

        return $this->messages = new MessageBag($errors->toArray());
    }

    public function getMessageBag()
    {
        return $this->messages;
    }

    public function validate()
    {
        if ($this->fails()) {
            throw new ValidationException($this);
        }
    }

    public function getRules()
    {
        return [];
    }

    private function convertDataPathToAttribute(string $dataPath): string
    {
        $dataPath = Str::substr($dataPath, 1);
        $dataPath = str_replace('/', '.', $dataPath);

        return $dataPath;
    }
}