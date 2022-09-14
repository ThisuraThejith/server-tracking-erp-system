<?php

namespace App\Entity;

use App\Repository\RamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RamRepository::class)]
class Ram
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $size = null;

    #[ORM\OneToMany(mappedBy: 'ram', targetEntity: ServerRam::class)]
    private Collection $serverRams;

    public function __construct()
    {
        $this->serverRams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return Collection<int, ServerRam>
     */
    public function getServerRams(): Collection
    {
        return $this->serverRams;
    }

    public function addServerRam(ServerRam $serverRam): self
    {
        if (!$this->serverRams->contains($serverRam)) {
            $this->serverRams->add($serverRam);
            $serverRam->setRam($this);
        }

        return $this;
    }

    public function removeServerRam(ServerRam $serverRam): self
    {
        if ($this->serverRams->removeElement($serverRam)) {
            // set the owning side to null (unless already changed)
            if ($serverRam->getRam() === $this) {
                $serverRam->setRam(null);
            }
        }

        return $this;
    }
}
