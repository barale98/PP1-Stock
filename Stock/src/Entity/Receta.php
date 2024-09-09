<?php

namespace App\Entity;

use App\Repository\RecetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetaRepository::class)]
class Receta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\ManyToOne(inversedBy: 'recetas')]
    private ?User $usuario = null;

    #[ORM\ManyToMany(targetEntity: Maquinaria::class, mappedBy: 'recetas')]
    private Collection $maquinarias;

    #[ORM\ManyToMany(targetEntity: Repuestos::class, mappedBy: 'recetas')]
    private Collection $repuestos;

    public function __construct()
    {
        $this->maquinarias = new ArrayCollection();
        $this->repuestos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * @return Collection<int, Maquinaria>
     */
    public function getMaquinarias(): Collection
    {
        return $this->maquinarias;
    }

    public function addMaquinaria(Maquinaria $maquinaria): static
    {
        if (!$this->maquinarias->contains($maquinaria)) {
            $this->maquinarias->add($maquinaria);
        }

        return $this;
    }

    public function removeMaquinaria(Maquinaria $maquinaria): static
    {
        $this->maquinarias->removeElement($maquinaria);

        return $this;
    }

    /**
     * @return Collection<int, Repuestos>
     */
    public function getRepuestos(): Collection
    {
        return $this->repuestos;
    }

    public function addRepuesto(Repuestos $repuesto): static
    {
        if (!$this->repuestos->contains($repuesto)) {
            $this->repuestos->add($repuesto);
        }

        return $this;
    }

    public function removeRepuesto(Repuestos $repuesto): static
    {
        $this->repuestos->removeElement($repuesto);

        return $this;
    }
}