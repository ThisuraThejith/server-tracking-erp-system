<?php

namespace App\Controller;


use App\Entity\Server;
use App\Entity\ServerRam;
use App\Repository\RamRepository;
use App\Repository\ServerRamRepository;
use App\Repository\ServerRepository;
use App\Service\RamService;
use App\Service\ServerService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

class ServerController extends BaseController
{
    public function __construct(
        private ServerRepository $serverRepository,
        private RamRepository $ramRepository,
        private ServerRamRepository $serverRamRepository,
        private ServerService $serverService,
        private RamService $ramService
    ) {
    }

    /**
     * @Route("/servers", name="createServer", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createServer(Request $request): JsonResponse
    {
        try {
            $this->validateCreateServer($request);
            $params = json_decode($request->getContent(), true);
            $server = new Server($params['assetId'], $params['brand'], $params['name'], $params['price']);
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
        }
    }

    /**
     * @Route("/servers", name="getAllServers", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function getAllServers(Request $request): Response
    {
        try {
            $servers = $this->serverRepository->getAllServers();
            $response = $this->getSerializer()->serialize($servers, 'json');
            return new Response($response);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(['message' => $e->getMessage(), 'status' => 'Failed'],
                Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/servers", name="deleteServers", methods={"DELETE"})
     * @param Request $request
     * @return Response
     */
    public function deleteServers(Request $request): JsonResponse
    {
        try {
            $this->validateDeleteServer($request);
            $params = json_decode($request->getContent(), true);
            $assetIds = $params['assetIds'];
            foreach ($assetIds as $assetId) {
                $server = $this->serverRepository->getServerByAssetId($assetId);
                $serverRams = $this->serverRamRepository->getServerRamsByServer($server);
                foreach ($serverRams as $serverRam) {
                    $this->serverRamRepository->remove($serverRam);
                }
                $this->serverRepository->remove($server);
            }
            return new JsonResponse(['message' => 'Successfully Deleted', 'status' => 'success']);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(['message' => $e->getMessage(), 'status' => 'Failed'],
                Response::HTTP_BAD_REQUEST);
        } catch (NoResultException $e) {
            return new JsonResponse(['message' => 'Server or server rams not found for the given asset Id or server', 'status' => 'Failed'],
                Response::HTTP_NOT_FOUND);
        } catch (NonUniqueResultException $e) {
            return new JsonResponse(['message' => 'Duplicate servers or server ram records exist for the given asset Id or server', 'status' => 'Failed'],
                Response::HTTP_CONFLICT);
        }
    }

    /**
     * @param Request $request
     */
    protected function validateCreateServer(Request $request) : void
    {
        // Checks whether the price of the created server is > 0
        $priceValidator = v::callback(
            function ($value) {
                return !($value <= 0);
            })->setName('Price should be greater than 0 - ');

        $existingAssetIds = $this->serverService->getAssetIdsList();
        $assetIdValidator = v::callback(
            function ($value) use ($existingAssetIds) {
                if (in_array($value, $existingAssetIds, true)) {
                    return false;
                }
                return true;
            })->setName('Asset Id is already existing - ');

        $existingRamIds = $this->ramService->getRamIdsList();

        $ramModulesNonExistingValidator = v::callback(
            function ($value) use ($existingRamIds) {
                $nonExistingRamIds = array_diff(array_keys($value), $existingRamIds);
                if ($nonExistingRamIds) {
                    return false;
                }
                return true;
            })->setName('Some of the passed Ram Ids are not existing - ');

        // Checks whether the created server contains at least one ram
        $atLeastOneRamModulePassedValidator = v::callback(
            function ($value) {
                if (count($value) === 0) {
                    return false;
                }
                return true;
            })->setName('At least one ram module should be added to the sever - ');

        $validator = v::keySet(
            v::key('assetId', v::allOf(v::intVal()->positive()->length(null, 9), $assetIdValidator), true),
            v::key('brand', v::stringVal()->notEmpty(), true),
            v::key('name', v::stringVal()->notEmpty(), true),
            v::key('price', v::allOf(v::floatVal()->notEmpty(), $priceValidator), true),
            v::key('ram_modules', v::allOf(v::arrayType(), $ramModulesNonExistingValidator, $atLeastOneRamModulePassedValidator), true)
        );
        $this->validate($validator, json_decode($request->getContent(), true));
    }

    /**
     * @param Request $request
     */
    protected function validateDeleteServer(Request $request) : void
    {
        $existingAssetIds = $this->serverService->getAssetIdsList();
        $assetIdsValidator = v::callback(
            function ($value) use ($existingAssetIds) {
                $nonExistingAssetIds = array_diff(array_values($value), $existingAssetIds);
                if ($nonExistingAssetIds) {
                    return false;
                }
                return true;
            })->setName('Some of the passed Asset Ids are not existing - ');

        $validator = v::keySet(
            v::key('assetIds', v::allOf(v::arrayType()->each(v::intVal()->positive()), $assetIdsValidator), true)
        );
        $this->validate($validator, json_decode($request->getContent(), true));
    }
}
