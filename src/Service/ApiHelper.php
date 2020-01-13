<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiHelper
{
    protected $serializer;
    protected $validator;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validation
    ){
        $this->serializer = $serializer;
        $this->validator = $validation;
    }

    /**
     * @param Request $request
     * @param int $methodInt [ 0 = POST, 1 = PUT, 2 = DELETE ]
     * @return bool
     */
    public function checkHeader(Request $request, int $methodInt = 0): bool
    {
        $methods = [
            'POST',
            'PUT',
            'DELETE'
        ];

        $header = $request->headers->contains('accept', 'application/json');
        $method = $request->isMethod($methods[$methodInt]);

        return $header && $method;
    }

    /**
     * @param string $data - json data in string format
     * @param string $model - entity class
     * @return array|object
     * @description runs Validate on given $data and $model class
     */
    public function validate(string $data, string $model){
        if (!$data) {
            throw new BadRequestHttpException('Empty body.');
        }

        try {
            $object = $this->serializer->deserialize($data, $model, 'json');
        } catch (\Exception $e) {
            throw new BadRequestHttpException('Invalid body.');
        }

        $err = $this->validator->validate($object);

        if($err->count()) {
            throw new BadRequestHttpException($err);
        }

        return $object;
    }
}