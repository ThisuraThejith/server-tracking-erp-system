<?php

namespace App\Controller;

use App\Entity\ServerRam;
use App\Repository\RamRepository;
use App\Repository\ServerRamRepository;
use App\Repository\ServerRepository;
use App\Service\RamService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

class ServerRamController extends BaseController
{
    public function __construct(
        private ServerRepository $serverRepository,
        private RamRepository $ramRepository,
        private ServerRamRepository $serverRamRepository,
        private RamService $ramService
    ) {
    }

    /**
     *
     * @api {post} /server/{assetId}/rams 1. Server Rams - Add
     * @apiDescription Adds ram modules to the server with the provided asset ID
     * @apiName addRamsToSever
     * @apiGroup Servers - Resource Requests
     * @apiParam {Integer} assetId - Asset ID of the server to which the ram modules are being added <br>
     * i.e. <b>{{apiUrl}}/server/123456789/rams<b>
     * @apiParam {JSON} Object - Parameters which are needed to add rams to the server <br>
     * <b>In the following parameter object example the object key should be the ID of the particular ram module. The
     * value should be the quantity of the particular ram module that needs to be added to the server.<b>
     * @apiParamExample {JSON} Parameter-Object-Example:
     *  {
     *       "ram_modules":
     *       {
     *           "3" : 2,
     *           "4" : 3
     *       }
     *  }
     * @apiSuccess {JSON} Object - Object containing success message and status
     *
     * @apiSuccessExample Success-Response:
     * {
     *       "message": "Successfully Saved",
     *       "status": "Success"
     * }
     *
     * @apiError (400) BadRequest Invalid parameters provided
     * @apiError (404) NotFound Server not found
     * @apiError (409) Conflict Duplicate servers exist
     */
    /**
     * @Route("/server/{assetId}/rams", name="addRamsToSever", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function addRamsToSever(Request $request, int $assetId): JsonResponse
    {
        try {
            $this->validateAddRamsToSever($request);
            $params = json_decode($request->getContent(), true);
            $server = $this->serverRepository->getServerByAssetId($assetId);
            $ram_modules = $this->ramRepository->findBy(['id' => array_keys((array)$params['ram_modules'])]);
            foreach($ram_modules as $ram) {
                $serverRam = new ServerRam($server, $ram, $params['ram_modules'][(string)$ram->getId()]);
                $server->addServerRam($serverRam);
                $this->serverRamRepository->persistServerRam($serverRam);
            }
            $this->serverRepository->add($server);
            return new JsonResponse(['message' => 'Successfully Saved', 'status' => 'Success']);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(['message' => $e->getMessage(), 'status' => 'Failed'],
                Response::HTTP_BAD_REQUEST);
        } catch (NoResultException $e) {
            return new JsonResponse(json_encode(['message' => 'Server with the given asset Id is not found', 'status' => 'Failed']),
                Response::HTTP_NOT_FOUND);
        } catch (NonUniqueResultException $e) {
            return new JsonResponse(json_encode(['message' => 'Duplicate servers exist for the given asset Id', 'status' => 'Failed']),
                Response::HTTP_CONFLICT);
        }
    }

    /**
     * @api {get} /server/{assetId}/rams  2. Server Rams - Get
     * @apiDescription Retrieves details of all the ram modules of a server
     * @apiName  getServerRamsOfServer
     * @apiGroup Servers - Resource Requests
     * @apiSuccess {JSON} Object - Object containing all the rams of the given server
     * @apiSuccessExample Success-Response:
     *  [
     *       {
     *           "id": 1,
     *           "quantity": 2,
     *           "ram": {
     *               "id": 1,
     *               "type": "DDR3",
     *               "size": 1
     *           }
     *       },
     *       {
     *           "id": 2,
     *           "quantity": 2,
     *           "ram": {
     *               "id": 3,
     *               "type": "DDR3",
     *               "size": 4
     *           }
     *       }
     *   ]
     * @apiError (400) BadRequest Unsupported request
     * @apiError (404) NotFound Server not found
     * @apiError (409) Conflict Duplicate servers exist
     */
    /**
     * @Route("/server/{assetId}/rams", name="getServerRamsOfServer", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function getServerRamsOfServer(Request $request, int $assetId): Response
    {
        try {
            $server = $this->serverRepository->getServerByAssetId($assetId);
            $serverRams = $this->serverRamRepository->getServerRamsAndRamsOfServer($server);
            $response = $this->getSerializer()->serialize($serverRams, 'json');
            return new Response($response);
        } catch (BadRequestHttpException $e) {
            return new Response(json_encode(['message' => $e->getMessage(), 'status' => 'Failed']),
                Response::HTTP_BAD_REQUEST);
        } catch (NoResultException $e) {
            return new Response(json_encode(['message' => 'Server with the given asset Id is not found', 'status' => 'Failed']),
                Response::HTTP_NOT_FOUND);
        } catch (NonUniqueResultException $e) {
            return new Response(json_encode(['message' => 'Duplicate servers exist for the given asset Id', 'status' => 'Failed']),
                Response::HTTP_CONFLICT);
        }
    }

    /**
     *
     * @api {delete} /server/{assetId}/rams 3. Server Rams - Delete
     * @apiDescription Removes ram modules of the given server when a list of ram ID is provided
     * @apiName removeRamsFromServer
     * @apiGroup Servers - Resource Requests
     * @apiParam {JSON} Object - List of ram IDs of the rams that's being removed from the given server
     * @apiParamExample {JSON} Parameter-Object-Example:
     *  {
     *      "ramIds": [4, 3]
     *  }
     * @apiSuccess {JSON} Object - Object containing success message and status
     *
     * @apiSuccessExample Success-Response:
     *  {
     *       "message": "Successfully Deleted",
     *       "status": "Success"
     *  }
     *
     * @apiError (400) BadRequest Invalid parameters provided
     * @apiError (404) NotFound Server or ram not found
     * @apiError (409) Conflict Duplicate servers or ram records exist
     */
    /**
     * @Route("/server/{assetId}/rams", name="removeRamsFromServer", methods={"DELETE"})
     * @param Request $request
     * @return Response
     */
    public function removeRamsFromServer(Request $request, int $assetId): JsonResponse
    {
        try {
            $this->validateRemoveRamsFromServer($request);
            $params = json_decode($request->getContent(), true);
            $ramIds = $params['ramIds'];
            $server = $this->serverRepository->getServerByAssetId($assetId);
            foreach ($ramIds as $ramId) {
                $ram = $this->ramRepository->getRamById($ramId);
                $serverRam = $this->serverRamRepository->getServerRamByRamAndServer($ram, $server);
                $this->serverRamRepository->remove($serverRam);
            }
            return new JsonResponse(['message' => 'Successfully Deleted', 'status' => 'Success']);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(['message' => $e->getMessage(), 'status' => 'Failed'],
                Response::HTTP_BAD_REQUEST);
        } catch (NoResultException $e) {
            return new JsonResponse(json_encode(['message' => 'Server or ram with the given asset or ram Id is not found', 'status' => 'Failed']),
                Response::HTTP_NOT_FOUND);
        } catch (NonUniqueResultException $e) {
            return new JsonResponse(json_encode(['message' => 'Duplicate servers or rams exist for the given asset or ram Id', 'status' => 'Failed']),
                Response::HTTP_CONFLICT);
        }
    }

    /**
     * @param Request $request
     */
    protected function validateAddRamsToSever(Request $request) : void
    {
        $existingRamIds = $this->ramService->getRamIdsList();

        $ramModulesNonExistingValidator = v::callback(
            function ($value) use ($existingRamIds) {
                $nonExistingRamIds = array_diff(array_keys($value), $existingRamIds);
                if ($nonExistingRamIds) {
                    return false;
                }
                return true;
            })->setName('Some of the passed Ram Ids are not existing - ');

        $validator = v::keySet(
            v::key('ram_modules', v::allOf(v::arrayType(), $ramModulesNonExistingValidator), true)
        );
        $this->validate($validator, json_decode($request->getContent(), true));
    }

    /**
     * @param Request $request
     */
    protected function validateRemoveRamsFromServer(Request $request) : void
    {
        $existingRamIds = $this->ramService->getRamIdsList();
        $ramIdsValidator = v::callback(
            function ($value) use ($existingRamIds) {
                $nonExistingRamIds = array_diff(array_values($value), $existingRamIds);
                if ($nonExistingRamIds) {
                    return false;
                }
                return true;
            })->setName('Some of the passed Ram Ids are not existing - ');

        $validator = v::keySet(
            v::key('ramIds', v::allOf(v::arrayType()->each(v::intVal()->positive()), $ramIdsValidator), true)
        );
        $this->validate($validator, json_decode($request->getContent(), true));
    }
}
