<?php
namespace App\Service;


use Symfony\Component\HttpFoundation\Request;

class ApiHelper
{
    public function checkHeader(Request $request): bool
    {
        $header = $request->headers->contains('accept', 'application/json');
        $method = $request->isMethod('POST');

        return $header && $method;
    }
}