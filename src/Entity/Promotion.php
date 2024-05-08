<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromotionRepository::class)]
class Promotion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $adjustment = null;

    #[ORM\Column(type: 'json')]
    private array $criteria = [];

    /**
     * @var Collection<int, ProductPromotion>
     */
    #[ORM\OneToMany(targetEntity: ProductPromotion::class, mappedBy: 'promotion', orphanRemoval: true)]
    private Collection $productPromotions;

    public function __construct()
    {
        $this->productPromotions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAdjustment(): ?float
    {
        return $this->adjustment;
    }

    public function setAdjustment(float $adjustment): static
    {
        $this->adjustment = $adjustment;

        return $this;
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function setCriteria(array $criteria): static
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * @return Collection<int, ProductPromotion>
     */
    public function getProductPromotions(): Collection
    {
        return $this->productPromotions;
    }

    public function addProductPromotion(ProductPromotion $productPromotion): static
    {
        if (!$this->productPromotions->contains($productPromotion)) {
            $this->productPromotions->add($productPromotion);
            $productPromotion->setPromotion($this);
        }

        return $this;
    }

    public function removeProductPromotion(ProductPromotion $productPromotion): static
    {
        if ($this->productPromotions->removeElement($productPromotion)) {
            // set the owning side to null (unless already changed)
            if ($productPromotion->getPromotion() === $this) {
                $productPromotion->setPromotion(null);
            }
        }

        return $this;
    }
}
