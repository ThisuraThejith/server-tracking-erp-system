<?php

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ServerRepository::class)]
#[UniqueEntity(fields: "assetId", message: "Asset ID is already taken.")]
class Server
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $assetId = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\OneToMany(mappedBy: 'server', targetEntity: ServerRam::class)]
    private Collection $serverRams;

    public function __construct()
    {
        $this->serverRams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAssetId(): ?int
    {
        return $this->assetId;
    }

    public function setAssetId(int $assetId): self
    {
        $this->assetId = $assetId;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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
            $serverRam->setServer($this);
        }

        return $this;
    }

    public function removeServerRam(ServerRam $serverRam): self
    {
        if ($this->serverRams->removeElement($serverRam)) {
            // set the owning side to null (unless already changed)
            if ($serverRam->getServer() === $this) {
                $serverRam->setServer(null);
            }
        }

        return $this;
    }
}
