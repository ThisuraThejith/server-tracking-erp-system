<?php

namespace App\Controller;


use App\Entity\Server;
use App\Entity\ServerRam;
use App\Repository\RamRepository;
use App\Repository\ServerRamRepository;
use App\Repository\ServerRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

class ServerController extends BaseController
{
    public function __construct(
        private ServerRepository $serverRepository,
        private RamRepository $ramRepository,
        private ServerRamRepository $serverRamRepository
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
     * @Route("/servers", name="createServer", methods={"GET"})
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
            return new Response(json_encode(['message' => $e->getMessage(), 'status' => 'Failed']),
                Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/servers", name="getServers", methods={"DELETE"})
     * @param Request $request
     * @return Response
     */
    public function deleteServers(Request $request): JsonResponse
    {
        try {
            $params = json_decode($request->getContent(), true);
            $assetIds = $params['assetIds'];
            foreach ($assetIds as $assetId) {
                $server = $this->serverRepository->getServerByAssetId($assetId);
                $serverRams = $this->serverRamRepository->getServerRamByServer($server);
                foreach ($serverRams as $serverRam) {
                    $this->serverRamRepository->remove($serverRam);
                }
                $this->serverRepository->remove($server);
            }
            return new JsonResponse(['message' => 'Successfully Deleted', 'status' => 'success']);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(['message' => $e->getMessage(), 'status' => 'Failed'],
                Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param Request $request
     */
    protected function validateCreateServer(Request $request) : void
    {
        $validator = v::keySet(
            v::key('assetId', v::intVal()->positive()->length(null, 9), true),
            v::key('brand', v::stringVal()->notEmpty(), true),
            v::key('name', v::stringVal()->notEmpty(), true),
            v::key('price', v::floatVal()->notEmpty(), true),
            v::key('ram_modules', v::any(), true)
        );
        $this->validate($validator, json_decode($request->getContent(), true));
    }
}
