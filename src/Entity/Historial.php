<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistorialRepository")
 */
class Historial
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $factura;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RenglonHistorial", mappedBy="historial", orphanRemoval=true)
     */
    private $RenglonHistorial;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="historiales")
     */
    private $Cliente;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $eliminado;

    /**
     * @ORM\ManyToOne(targetEntity=Proveedor::class, inversedBy="historiales")
     */
    private $proveedor;

    public function __construct()
    {
        $this->RenglonHistorial = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getFactura(): ?bool
    {
        return $this->factura;
    }

    public function setFactura(bool $factura): self
    {
        $this->factura = $factura;

        return $this;
    }

    /**
     * @return Collection|RenglonHistorial[]
     */
    public function getRenglonHistorial(): Collection
    {
        return $this->RenglonHistorial;
    }

    public function addRenglonHistorial(RenglonHistorial $renglonHistorial): self
    {
        if (!$this->RenglonHistorial->contains($renglonHistorial)) {
            $this->RenglonHistorial[] = $renglonHistorial;
            $renglonHistorial->setHistorial($this);
        }

        return $this;
    }

    public function removeRenglonHistorial(RenglonHistorial $renglonHistorial): self
    {
        if ($this->RenglonHistorial->contains($renglonHistorial)) {
            $this->RenglonHistorial->removeElement($renglonHistorial);
            // set the owning side to null (unless already changed)
            if ($renglonHistorial->getHistorial() === $this) {
                $renglonHistorial->setHistorial(null);
            }
        }

        return $this;
    }

    public function getCliente(): ?Cliente
    {
        return $this->Cliente;
    }

    public function setCliente(?Cliente $Cliente): self
    {
        $this->Cliente = $Cliente;

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

    public function getProveedor(): ?Proveedor
    {
        return $this->proveedor;
    }

    public function setProveedor(?Proveedor $proveedor): self
    {
        $this->proveedor = $proveedor;

        return $this;
    }
}
