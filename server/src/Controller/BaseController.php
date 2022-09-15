<?php

namespace App\Controller;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validatable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BaseController extends AbstractController
{
    protected function getSerializer(): Serializer
    {
        return new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    /**
     * @param Validatable $validator
     * @param array $parameters
     */
    protected function validate(Validatable $validator, array $parameters) : void {
        try {
            $validator->assert($parameters);
        } catch (NestedValidationException $e) {
            throw new BadRequestHttpException(
                preg_replace("/".PHP_EOL."/", "\n",
                    $e->getFullMessage())
            );
        }
    }
}