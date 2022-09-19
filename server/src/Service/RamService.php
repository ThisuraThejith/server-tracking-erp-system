<?php

namespace App\Service;

use App\Repository\RamRepository;
use App\Repository\ServerRamRepository;
use App\Repository\ServerRepository;

class RamService
{
    public function __construct(
        private ServerRepository $serverRepository,
        private RamRepository $ramRepository,
        private ServerRamRepository $serverRamRepository
    ) {
    }

    public function getRamIdsList()
    {
        $ramIds = array();
        $rams = $this->ramRepository->getAllRams();

        foreach ($rams as $ram) {
            $ramIds[] = $ram['id'];
        }

        return $ramIds;
    }
}