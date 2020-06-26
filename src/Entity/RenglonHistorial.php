<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RenglonHistorialRepository")
 */
class RenglonHistorial
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $detalle;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cantidad;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="No puede dejar en blanco")
     */
    private $precio;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Producto", inversedBy="renglonHistoriales")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Elija un producto")
     * 
     */
    private $Producto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Historial", inversedBy="RenglonHistorial")
     * @ORM\JoinColumn(nullable=false)
     */
    private $historial;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetalle(): ?string
    {
        return $this->detalle;
    }

    public function setDetalle(?string $detalle): self
    {
        $this->detalle = $detalle;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(?int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getProducto(): ?Producto
    {
        return $this->Producto;
    }

    public function setProducto(?Producto $Producto): self
    {
        $this->Producto = $Producto;

        return $this;
    }

    public function getHistorial(): ?Historial
    {
        return $this->historial;
    }

    public function setHistorial(?Historial $historial): self
    {
        $this->historial = $historial;

        return $this;
    }
}
