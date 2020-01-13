<?php
namespace App\Service;


use JsonSchema\Validator as JsonValidator;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class ApiValidator
{
    public function runValidation(Request $request)
    {
        $data = json_decode($request->getContent(), true);
            //$this->container->get('form.factory')->create($type, $data, $options);
        /*$jValidator = new JsonValidator();
        $jValidator->validate($data, (object)[
            'type' => 'array',
            'properties' => (object)[
                'title' => (object)[
                    'type' => 'string'
                ],
                'content' => (object)[
                    'type' => 'string'
                ],
                'cratedAt' => (object)[
                    'type' => 'datetime'
                ],
                'inStatus' => (object)[
                    'type' => 'number',
                    'require' => true
                ]
            ],
            'required' => ['title', 'inStatus']
        ]);*/
    }

    private function simpleValidation($data)
    {

    }
}