<?php
declare(strict_types=1);

namespace App\Entity;

use App\Enum\EventStatusEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: 'events')]
class Event
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $organizerName;

    #[ORM\Column(length: 100, unique: true)]
    private string $inviteCode;

    #[ORM\Column]
    private int $maxPlayers;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $giftBudget;

    #[ORM\Column(type: 'string', enumType: EventStatusEnum::class)]
    private EventStatusEnum $status;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    /**
     * @var ArrayCollection|Collection|Player[] $players
     */
    #[ORM\OneToMany(targetEntity: Player::class, mappedBy: 'event', cascade: ['persist'], orphanRemoval: true)]
    private Collection|ArrayCollection|array $players;

    /**
     * @var Collection|ArrayCollection|Gift[] $gifts
     */
    #[ORM\OneToMany(targetEntity: Gift::class, mappedBy: 'event', cascade: ['persist'], orphanRemoval: true)]
    private Collection|ArrayCollection|array $gifts;

    /**
     * @var Collection|ArrayCollection|GiftAssignment[] $assignments
     */
    #[ORM\OneToMany(targetEntity: GiftAssignment::class, mappedBy: 'event', cascade: ['persist'], orphanRemoval: true)]
    private Collection|ArrayCollection|array $assignments;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->gifts = new ArrayCollection();
        $this->assignments = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->status = EventStatusEnum::Running;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrganizerName(): string
    {
        return $this->organizerName;
    }

    public function setOrganizerName(string $organizerName): self
    {
        $this->organizerName = $organizerName;
        return $this;
    }

    public function getInviteCode(): string
    {
        return $this->inviteCode;
    }

    public function setInviteCode(string $inviteCode): self
    {
        $this->inviteCode = $inviteCode;
        return $this;
    }

    public function getMaxPlayers(): int
    {
        return $this->maxPlayers;
    }

    public function setMaxPlayers(int $maxPlayers): self
    {
        $this->maxPlayers = $maxPlayers;
        return $this;
    }

    public function getGiftBudget(): float
    {
        return (float) $this->giftBudget;
    }

    public function setGiftBudget(float $giftBudget): self
    {
        $this->giftBudget = (string) $giftBudget;
        return $this;
    }

    public function getStatus(): EventStatusEnum
    {
        return $this->status;
    }

    public function setStatus(EventStatusEnum $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function getGifts(): Collection
    {
        return $this->gifts;
    }

    public function getAssignments(): Collection
    {
        return $this->assignments;
    }
}
