<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GiftAssignmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GiftAssignmentRepository::class)]
#[ORM\Table(name: 'gift_assignments')]
class GiftAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'assignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gift $gift = null;

    #[ORM\OneToOne(targetEntity: Player::class, inversedBy: 'assignment')]
    #[ORM\JoinColumn(nullable: false)]
    private Player $receiver;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getGift(): ?Gift
    {
        return $this->gift;
    }

    public function setGift(?Gift $gift): self
    {
        $this->gift = $gift;

        return $this;
    }

    public function getReceiver(): ?Player
    {
        return $this->receiver;
    }

    public function setReceiver(?Player $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }
}
