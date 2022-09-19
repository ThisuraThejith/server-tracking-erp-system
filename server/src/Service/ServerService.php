<?php

namespace App\Service;

use App\Repository\ServerRepository;

class ServerService
{
    public function __construct(
        private ServerRepository $serverRepository
    ) {
    }

    public function getAssetIdsList()
    {
        $assetIds = array();
        $servers = $this->serverRepository->getAllServers();

        foreach ($servers as $server) {
            $assetIds[] = $server['assetId'];
        }

        return $assetIds;
    }
}