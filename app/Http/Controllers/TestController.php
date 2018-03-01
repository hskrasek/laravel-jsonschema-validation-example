<?php

namespace App\Http\Controllers;

use App\Validation\JsonSchemaValidator;
use HSkrasek\JSONSchema\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $this->validateWith($this->createJsonSchemaValidator($request, 'test-schema.json'));

        return \response(null, 201);
    }

    private function createJsonSchemaValidator(Request $request, string $schema): JsonSchemaValidator
    {
        return new JsonSchemaValidator(
            new Validator(
                (object)$request->input(),
                json_decode(file_get_contents(storage_path('schemas/' . $schema)))
            )
        );
    }
}
