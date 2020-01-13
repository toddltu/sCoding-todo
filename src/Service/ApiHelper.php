<?php
namespace App\Service;


use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiHelper
{
    public function checkHeader(Request $request): bool
    {
        $header = $request->headers->contains('accept', 'application/json');
        $method = $request->isMethod('POST');

        return $header && $method;
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    public function getErrorMessages(FormInterface $form): array
    {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $errors[$key] = $template;
        }
        if ($form->count()) {
            /** @var Form $child */
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        }
        return $errors;
    }
}