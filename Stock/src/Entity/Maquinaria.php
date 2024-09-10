<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaquinariaRepository::class)]
class Maquinaria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cantidad = null;

    #[ORM\ManyToMany(targetEntity: Receta::class, inversedBy: 'maquinarias')]
    private Collection $recetas;

    public function __construct()
    {
        $this->recetas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    public function addCantidad(int $cantidad): static
    {
        $this->cantidad += $cantidad;
        return $this;
    }

    public function subtractCantidad(int $cantidad): static
    {
        if ($this->cantidad >= $cantidad) {
            $this->cantidad -= $cantidad;
        } else {
            throw new \Exception('No hay suficiente stock para restar');
        }
        return $this;
    }

    public function hasSufficientStock(int $cantidad): bool
    {
        return $this->cantidad >= $cantidad;
    }

    /**
     * @return Collection<int, Receta>
     */
    public function getRecetas(): Collection
    {
        return $this->recetas;
    }

    public function addReceta(Receta $receta): static
    {
        if (!$this->recetas->contains($receta)) {
            $this->recetas->add($receta);
            $receta->addMaquinaria($this);  // Asegura que la relación sea bidireccional
        }

        return $this;
    }

    public function removeReceta(Receta $receta): static
    {
        if ($this->recetas->removeElement($receta)) {
            $receta->removeMaquinaria($this);  // Remueve la relación también en el otro lado
        }
        return $this;
    }
}