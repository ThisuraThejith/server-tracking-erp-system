<?php

namespace App\Tests\Entity;

use App\Entity\Ram;
use App\Entity\Server;
use App\Entity\ServerRam;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class ServerRamTest extends TestCase
{
    private ServerRam $serverRam;

    public function setUp(): void
    {
        parent::setUp();
        $server = new Server(assetId: 12345, brand: 'HP', name: 'Inspiron', price: 450.75);
        $ram = new Ram(type: 'DDR3', size: 4);
        $this->serverRam = new ServerRam(server: $server, ram: $ram, quantity: 2);
    }

    public function testGetSetServer(): void
    {
        $server = new Server(assetId: 123456, brand: 'Acer', name: 'Latitude', price: 500.50);
        $this->serverRam->setServer($server);

        $result = $this->serverRam->getServer();
        $this->assertInstanceOf(Server::class, $result);
        $this->assertEquals('Acer', $result->getBrand());
        $this->assertEquals('Latitude', $result->getName());
        $this->assertEquals(500.50, $result->getPrice());
    }

    public function testGetSetRam(): void
    {
        $ram = new Ram(type: 'DDR4', size: 8);
        $this->serverRam->setRam($ram);

        $result = $this->serverRam->getRam();
        $this->assertInstanceOf(Ram::class, $result);
        $this->assertEquals('DDR4', $result->getType());
        $this->assertEquals(8, $result->getSize());
    }

    public function testGetSetQuantity(): void
    {
        $this->serverRam->setQuantity(4);

        $this->assertEquals(4, $this->serverRam->getQuantity());
    }
}