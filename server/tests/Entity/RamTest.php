<?php

use App\Entity\Invitation;
use App\Entity\Status;
use App\Entity\User;
use App\Entity\UserInvitation;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class InvitationTest extends TestCase
{
    private Invitation $invitation;

    public function setUp(): void
    {
        parent::setUp();
        $this->invitation = new Invitation();
    }

    public function testGetSetTitle(): void
    {
        $this->invitation->setTitle('Title 1');

        $this->assertEquals('Title 1', $this->invitation->getTitle());
    }

    public function testGetSetDescription(): void
    {
        $this->invitation->setDescription('Description 1');

        $this->assertEquals('Description 1', $this->invitation->getDescription());
    }

    public function testGetSetDateTime(): void
    {
        $this->invitation->setDateTime(new \DateTime());

        $this->assertInstanceOf(DateTime::class, $this->invitation->getDateTime());
    }

    public function testGetSetSender(): void
    {
        $sender = new User();
        $sender->setUsername('username1');
        $sender->setPassword('password1');
        $sender->setFirstName('firstname1');
        $sender->setLastName('lastname1');

        $this->invitation->setSender($sender);

        $result = $this->invitation->getSender();
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('username1', $result->getUsername());
        $this->assertEquals('password1', $result->getPassword());
        $this->assertEquals('firstname1', $result->getFirstName());
        $this->assertEquals('lastname1', $result->getLastName());
    }

    public function testGetAddRemoveUserInvitation(): void
    {
        $user = new User();
        $user->setUsername('username1');
        $user->setPassword('password1');
        $user->setFirstName('firstname1');
        $user->setLastName('lastname1');

        $this->invitation->setTitle('Title 1');
        $this->invitation->setDescription('Description 1');
        $this->invitation->setDateTime(new \DateTime());
        $this->invitation->setSender($user);

        $status = new Status();
        $status->setName('status1');

        $userInvitation = new UserInvitation();
        $userInvitation->setUser($user);
        $userInvitation->setInvitation($this->invitation);
        $userInvitation->setStatus($status);

        $result = $this->invitation->addUserInvitation($userInvitation);
        $this->assertInstanceOf(Invitation::class, $result);

        $result = $this->invitation->getUserInvitations();
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(UserInvitation::class, $result[0]);
        $this->assertInstanceOf(User::class, $result[0]->getUser());
        $this->assertEquals('username1', $result[0]->getUser()->getUserName());
        $this->assertInstanceOf(Invitation::class, $result[0]->getInvitation());
        $this->assertEquals('Title 1', $result[0]->getInvitation()->getTitle());
        $this->assertInstanceOf(Status::class, $result[0]->getStatus());
        $this->assertEquals('status1', $result[0]->getStatus()->getName());

        $result = $this->invitation->removeUserInvitation($userInvitation);
        $this->assertInstanceOf(Invitation::class, $result);

        $result = $this->invitation->getUserInvitations();
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEmpty($result[0]);
    }
}