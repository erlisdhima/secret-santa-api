<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ORM\Table(name: 'players')]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\ManyToOne(inversedBy: 'players')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    /**
     * @var Collection|ArrayCollection|Preference[] $preferences
     */
    #[ORM\OneToMany(targetEntity: Preference::class, mappedBy: 'player', cascade: ['persist'], orphanRemoval: true)]
    private Collection|ArrayCollection|array $preferences;

    #[ORM\OneToOne(targetEntity: Gift::class, mappedBy: 'giver', cascade: ['persist'])]
    private ?Gift $gift = null;

    #[ORM\OneToOne(targetEntity: GiftAssignment::class, mappedBy: 'receiver', cascade: ['persist'])]
    private ?GiftAssignment $assignment = null;

    public function __construct()
    {
        $this->preferences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
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

    public function getPreferences(): Collection
    {
        return $this->preferences;
    }

    public function addPreference(Preference $preference): self
    {
        if (!$this->preferences->contains($preference)) {
            $this->preferences->add($preference);
            $preference->setPlayer($this);
        }

        return $this;
    }

    public function removePreference(Preference $preference): self
    {
        if ($this->preferences->removeElement($preference)) {
            if ($preference->getPlayer() === $this) {
                $preference->setPlayer(null);
            }
        }

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

    public function getAssignment(): ?GiftAssignment
    {
        return $this->assignment;
    }

    public function setAssignment(?GiftAssignment $assignment): self
    {
        $this->assignment = $assignment;
        return $this;
    }
}
