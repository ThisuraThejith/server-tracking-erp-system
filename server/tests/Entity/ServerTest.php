<?php

namespace App\Tests\Entity;

use App\Entity\Ram;
use App\Entity\Server;
use App\Entity\ServerRam;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    private Server $server;

    public function setUp(): void
    {
        parent::setUp();
        $this->server = new Server(assetId: 12345, brand: 'HP', name: 'Inspiron', price: 450.75);
    }

    public function testGetSetAssetId(): void
    {
        $this->server->setAssetId(123456);

        $this->assertEquals(123456, $this->server->getAssetId());
    }

    public function testGetSetBrand(): void
    {
        $this->server->setBrand('Acer');

        $this->assertEquals('Acer', $this->server->getBrand());
    }

    public function testGetSetName(): void
    {
        $this->server->setName('Latitude');

        $this->assertEquals('Latitude', $this->server->getName());
    }

    public function testGetSetPrice(): void
    {
        $this->server->setPrice(500.00);

        $this->assertEquals(500.00, $this->server->getPrice());
    }

    public function testGetAddRemoveServerRams(): void
    {
        $ram = new Ram(type: 'DDR3', size: 4);

        $serverRam = new ServerRam(server: $this->server, ram: $ram, quantity: 2);

        $result = $this->server->addServerRam($serverRam);
        $this->assertInstanceOf(Server::class, $result);

        $result = $this->server->getServerRams();
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

        $result = $this->server->removeServerRam($serverRam);
        $this->assertInstanceOf(Server::class, $result);

        $result = $this->server->getServerRams();
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEmpty($result[0]);
    }

    public function testGetRams(): void
    {
        $ram = new Ram(type: 'DDR3', size: 4);

        $serverRam = new ServerRam(server: $this->server, ram: $ram, quantity: 2);

        $result = $this->server->addServerRam($serverRam);
        $this->assertInstanceOf(Server::class, $result);

        $result = $this->server->getRams();
        $this->assertIsArray($result);
        $this->assertInstanceOf(Ram::class, $result[0]);
        $this->assertEquals('DDR3', $result[0]->getType());
        $this->assertEquals(4, $result[0]->getSize());
    }
}