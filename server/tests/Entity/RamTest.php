<?php

namespace App\Tests\Entity;

use App\Entity\Ram;
use App\Entity\Server;
use App\Entity\ServerRam;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class RamTest extends TestCase
{
    private Ram $ram;

    public function setUp(): void
    {
        parent::setUp();
        $this->ram = new Ram(type: 'DDR3', size: 4);
    }

    public function testGetSetType(): void
    {
        $this->ram->setType('DDR4');

        $this->assertEquals('DDR4', $this->ram->getType());
    }

    public function testGetSetSize(): void
    {
        $this->ram->setSize(8);

        $this->assertEquals(8, $this->ram->getSize());
    }

    public function testGetAddRemoveServerRams(): void
    {
        $server = new Server(assetId: 12345, brand: 'HP', name: 'Inspiron', price: 450.75);

        $serverRam = new ServerRam(server: $server, ram: $this->ram, quantity: 2);

        $result = $this->ram->addServerRam($serverRam);
        $this->assertInstanceOf(Ram::class, $result);

        $result = $this->ram->getServerRams();
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(ServerRam::class, $result[0]);
        $this->assertInstanceOf(Ram::class, $result[0]->getRam());
        $this->assertEquals('DDR3', $result[0]->getRam()->getType());
        $this->assertEquals(4, $result[0]->getRam()->getSize());
        $this->assertInstanceOf(Server::class, $result[0]->getServer());
        $this->assertEquals(12345, $result[0]->getServer()->getAssetId());
        $this->assertEquals('HP', $result[0]->getServer()->getBrand());
        $this->assertEquals('Inspiron', $result[0]->getServer()->getName());
        $this->assertEquals(450.75, $result[0]->getServer()->getPrice());

        $result = $this->ram->removeServerRam($serverRam);
        $this->assertInstanceOf(Ram::class, $result);

        $result = $this->ram->getServerRams();
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEmpty($result[0]);
    }

    public function testGetServers(): void
    {
        $server = new Server(assetId: 12345, brand: 'HP', name: 'Inspiron', price: 450.75);

        $serverRam = new ServerRam(server: $server, ram: $this->ram, quantity: 2);

        $result = $this->ram->addServerRam($serverRam);
        $this->assertInstanceOf(Ram::class, $result);

        $result = $this->ram->getServers();
        $this->assertIsArray($result);
        $this->assertInstanceOf(Server::class, $result[0]);
        $this->assertEquals(12345, $result[0]->getAssetId());
        $this->assertEquals('HP', $result[0]->getBrand());
        $this->assertEquals('Inspiron', $result[0]->getName());
        $this->assertEquals(450.75, $result[0]->getPrice());
    }
}