<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\PreferenceTypeEnum;
use App\Repository\PreferenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PreferenceRepository::class)]
#[ORM\Table(name: 'preferences')]
class Preference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $value;

    #[ORM\Column(type: 'string', enumType: PreferenceTypeEnum::class)]
    private PreferenceTypeEnum $type;

    #[ORM\ManyToOne(inversedBy: 'preferences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getType(): PreferenceTypeEnum
    {
        return $this->type;
    }

    public function setType(PreferenceTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }
}
