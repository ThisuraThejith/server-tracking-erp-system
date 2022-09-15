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

class ServerRamController extends BaseController
{
    public function __construct(
        private ServerRepository $serverRepository,
        private RamRepository $ramRepository,
        private ServerRamRepository $serverRamRepository
    ) {
    }

    /**
     * @Route("/server/{assetId}/rams", name="addRamToSever", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function addRamsToSever(Request $request, int $assetId): JsonResponse
    {
        try {
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
        }
    }

    /**
     * @Route("/server/{assetId}/rams", name="getRamsOfServer", methods={"GET"})
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
        }
    }

    /**
     * @Route("/server/{assetId}/rams", name="removeRamsFromServer", methods={"DELETE"})
     * @param Request $request
     * @return Response
     */
    public function removeRamsFromServer(Request $request, int $assetId): JsonResponse
    {
        try {
            $params = json_decode($request->getContent(), true);
            $ramIds = $params['ramIds'];
            foreach ($ramIds as $ramId) {
                $ram = $this->ramRepository->getRamById($ramId);
                $server = $this->serverRepository->getServerByAssetId($assetId);
                $serverRam = $this->serverRamRepository->getServerRamByRamAndServer($ram, $server);
                $this->serverRamRepository->remove($serverRam);
            }
            return new JsonResponse(['message' => 'Successfully Deleted', 'status' => 'success']);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(['message' => $e->getMessage(), 'status' => 'Failed'],
                Response::HTTP_BAD_REQUEST);
        }
    }
}
