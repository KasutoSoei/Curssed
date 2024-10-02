<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $product_name = null;

    #[ORM\Column(length: 255)]
    private ?string $product_description = null;

    #[ORM\Column]
    private ?int $product_price = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\OneToMany(targetEntity: Category::class, mappedBy: 'product')]
    private Collection $product_category;

    #[ORM\Column(length: 255)]
    private ?string $seller_id = null;

    #[ORM\Column(length: 255)]
    private ?string $buyer_id = null;

    #[ORM\Column(length: 255)]
    private ?string $product_image = null;

    #[ORM\Column]
    private ?int $product_fav = null;

    public function __construct()
    {
        $this->product_category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function setProductName(string $product_name): static
    {
        $this->product_name = $product_name;

        return $this;
    }

    public function getProductDescription(): ?string
    {
        return $this->product_description;
    }

    public function setProductDescription(string $product_description): static
    {
        $this->product_description = $product_description;

        return $this;
    }

    public function getProductPrice(): ?int
    {
        return $this->product_price;
    }

    public function setProductPrice(int $product_price): static
    {
        $this->product_price = $product_price;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getProductCategory(): Collection
    {
        return $this->product_category;
    }

    public function addProductCategory(Category $productCategory): static
    {
        if (!$this->product_category->contains($productCategory)) {
            $this->product_category->add($productCategory);
            $productCategory->setName($this);
        }

        return $this;
    }

    public function removeProductCategory(Category $productCategory): static
    {
        if ($this->product_category->removeElement($productCategory)) {
            // set the owning side to null (unless already changed)
            if ($productCategory->getName() === $this) {
                $productCategory->setName(null);
            }
        }

        return $this;
    }

    public function getSellerId(): ?string
    {
        return $this->seller_id;
    }

    public function setSellerId(string $seller_id): static
    {
        $this->seller_id = $seller_id;

        return $this;
    }

    public function getBuyerId(): ?string
    {
        return $this->buyer_id;
    }

    public function setBuyerId(string $buyer_id): static
    {
        $this->buyer_id = $buyer_id;

        return $this;
    }

    public function getProductImage(): ?string
    {
        return $this->product_image;
    }

    public function setProductImage(string $product_image): static
    {
        $this->product_image = $product_image;

        return $this;
    }

    public function getProductFav(): ?int
    {
        return $this->product_fav;
    }

    public function setProductFav(int $product_fav): static
    {
        $this->product_fav = $product_fav;

        return $this;
    }
}
