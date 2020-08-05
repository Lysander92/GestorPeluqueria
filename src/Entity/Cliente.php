<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClienteRepository")
 */
class Cliente
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="No puede dejar en blanco")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="No puede dejar en blanco")
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $telefono;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Historial", mappedBy="Cliente")
     */
    private $historiales;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $eliminado;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="Cliente")
     */
    private $usuario;

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

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

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

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
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
            $historiale->setCliente($this);
        }

        return $this;
    }

    public function removeHistoriale(Historial $historiale): self
    {
        if ($this->historiales->contains($historiale)) {
            $this->historiales->removeElement($historiale);
            // set the owning side to null (unless already changed)
            if ($historiale->getCliente() === $this) {
                $historiale->setCliente(null);
            }
        }

        return $this;
    }
    
    public function __toString() {
        return "$this->apellido, $this->nombre";
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

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }
}
