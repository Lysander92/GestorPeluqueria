<?php

namespace App\Entity;

use App\Repository\ProveedorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProveedorRepository::class)
 */
class Proveedor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telefono;

    /**
     * @ORM\OneToMany(targetEntity=Historial::class, mappedBy="proveedor")
     */
    private $historiales;

    /**
     * @ORM\Column(type="boolean")
     */
    private $eliminado;

    public function __construct()
    {
        $this->historiales = new ArrayCollection();
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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(?int $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * @return Collection|Historial[]
     */
    public function getHistoriales(): Collection
    {
        return $this->historiales;
    }

    public function addHistoriale(Historial $historiale): self
    {
        if (!$this->historiales->contains($historiale)) {
            $this->historiales[] = $historiale;
            $historiale->setProveedor($this);
        }

        return $this;
    }

    public function removeHistoriale(Historial $historiale): self
    {
        if ($this->historiales->contains($historiale)) {
            $this->historiales->removeElement($historiale);
            // set the owning side to null (unless already changed)
            if ($historiale->getProveedor() === $this) {
                $historiale->setProveedor(null);
            }
        }

        return $this;
    }

    public function getEliminado(): ?bool
    {
        return $this->eliminado;
    }

    public function setEliminado(bool $eliminado): self
    {
        $this->eliminado = $eliminado;

        return $this;
    }
    
    public function __toString() {
        return $this->nombre;
    }
}
