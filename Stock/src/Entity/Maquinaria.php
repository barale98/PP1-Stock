<?php

namespace App\Entity;

use App\Repository\MaquinariaRepository;
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

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $marca = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(nullable: true)]
    private ?string $imagen = null;

    #[ORM\Column]
    private ?int $cantidad = null;

    #[ORM\Column(nullable: true)]
    private ?int $añosUso = null; // Nuevo campo

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $ultimoService = null;

    #[ORM\ManyToMany(targetEntity: Receta::class, inversedBy: 'maquinarias')]
    private Collection $recetas;

    public function __construct()
    {
        $this->recetas = new ArrayCollection();
        $this->nombre = '';
        $this->cantidad = 0;
    }

    public function getUltimoService(): ?\DateTimeInterface
    {
        return $this->ultimoService;
    }

    public function setUltimoService(?\DateTimeInterface $ultimoService): self
    {
        $this->ultimoService = $ultimoService;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getMarca(): ?string
    {
        return $this->marca;
    }

    public function setMarca(string $marca): self
    {
        $this->marca = $marca;
        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;
        return $this;
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

    public function getAñosUso(): ?int
    {
        return $this->añosUso;
    }

    public function setAñosUso(?int $añosUso): self
    {
        $this->añosUso = $añosUso;
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
            $receta->addMaquinaria($this);
        }
        return $this;
    }

    public function removeReceta(Receta $receta): static
    {
        if ($this->recetas->removeElement($receta)) {
            $receta->removeMaquinaria($this);
        }
        return $this;
    }
}