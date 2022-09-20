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
     * @api {get} /rams  1. Rams - Get
     * @apiDescription Retrieves details of all the rams
     * @apiName  getAllRams
     * @apiGroup Rams - Collection Requests
     * @apiSuccess {JSON} Object - Object containing all the rams
     * @apiSuccessExample Success-Response:
     *  [
            {
                "id": 1,
                "type": "DDR3",
                "size": 1
            },
            {
                "id": 2,
                "type": "DDR3",
                "size": 2
            },
            {
                "id": 3,
                "type": "DDR3",
                "size": 4
            },
            {
                "id": 4,
                "type": "DDR3",
                "size": 8
            },
            {
                "id": 5,
                "type": "DDR4",
                "size": 1
            },
            {
                "id": 6,
                "type": "DDR4",
                "size": 2
            },
            {
                "id": 7,
                "type": "DDR4",
                "size": 4
            },
            {
                "id": 8,
                "type": "DDR4",
                "size": 8
            }
        ]
     * @apiError (400) BadRequest Unsupported request
     */
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
