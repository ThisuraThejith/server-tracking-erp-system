<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validatable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BaseController extends AbstractController
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    protected function getSerializer(): Serializer
    {
        return new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    // User with id 1 is considered as the logged in user
    // Ideally this should be handled by authentication
    protected function getLoggedInUser(): User
    {
        return $this->userRepository->find(1);
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