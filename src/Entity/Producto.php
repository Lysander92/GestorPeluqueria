<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductoRepository")
 */
class Producto
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $marca;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cantidad;

    /**
     * @ORM\Column(type="float", nullable=false)
     * @Assert\NotBlank(message="No puede dejar en blanco")
     */
    private $precio;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RenglonHistorial", mappedBy="Producto")
     */
    private $renglonHistoriales;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $eliminado;

    /**
     * @ORM\Column(type="boolean")
     */
    private $controlStock;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="Productos")
     */
    private $usuario;

    public function __construct()
    {
        $this->renglonHistoriales = new ArrayCollection();
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

    public function setMarca(?string $marca): self
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

    /**
     * @return Collection|RenglonHistorial[]
     */
    public function getRenglonHistoriales(): Collection
    {
        return $this->renglonHistoriales;
    }

    public function addRenglonHistoriale(RenglonHistorial $renglonHistoriale): self
    {
        if (!$this->renglonHistoriales->contains($renglonHistoriale)) {
            $this->renglonHistoriales[] = $renglonHistoriale;
            $renglonHistoriale->setProducto($this);
        }

        return $this;
    }

    public function removeRenglonHistoriale(RenglonHistorial $renglonHistoriale): self
    {
        if ($this->renglonHistoriales->contains($renglonHistoriale)) {
            $this->renglonHistoriales->removeElement($renglonHistoriale);
            // set the owning side to null (unless already changed)
            if ($renglonHistoriale->getProducto() === $this) {
                $renglonHistoriale->setProducto(null);
            }
        }

        return $this;
    }
    
    public function __toString() {
    if ($this->marca == null){
            return $this->nombre;
        }
        else{
            return "$this->nombre, $this->marca";
        }
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

    public function getControlStock(): ?bool
    {
        return $this->controlStock;
    }

    public function setControlStock(bool $controlStock): self
    {
        $this->controlStock = $controlStock;

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
