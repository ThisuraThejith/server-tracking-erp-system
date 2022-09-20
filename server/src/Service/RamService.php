<?php

namespace App\Service;

use App\Repository\RamRepository;

class RamService
{
    public function __construct(
        private RamRepository $ramRepository
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