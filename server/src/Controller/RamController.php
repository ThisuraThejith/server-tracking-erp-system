<?php

namespace App\Controller;


use App\Repository\RamRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RamController extends BaseController
{
    public function __construct(
        private RamRepository $ramRepository
    ) {
    }

    /**
     * @Route("/rams", name="getAllRams", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function getAllRams(Request $request): Response
    {
        try {
            $rams = $this->ramRepository->getAllRams();
            $response = $this->getSerializer()->serialize($rams, 'json');
            return new Response($response);
        } catch (BadRequestHttpException $e) {
            return new Response(json_encode(['message' => $e->getMessage(), 'status' => 'Failed']),
                Response::HTTP_BAD_REQUEST);
        }
    }
}
