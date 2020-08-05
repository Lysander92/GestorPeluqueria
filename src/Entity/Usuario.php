<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 */
class Usuario implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Cliente::class, mappedBy="usuario")
     */
    private $Cliente;

    /**
     * @ORM\OneToMany(targetEntity=Proveedor::class, mappedBy="usuario")
     */
    private $Proveedores;

    /**
     * @ORM\OneToMany(targetEntity=Producto::class, mappedBy="usuario")
     */
    private $Productos;

    /**
     * @ORM\OneToMany(targetEntity=Historial::class, mappedBy="usuario")
     */
    private $Historiales;

    public function __construct()
    {
        $this->Cliente = new ArrayCollection();
        $this->Proveedores = new ArrayCollection();
        $this->Productos = new ArrayCollection();
        $this->Historiales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Cliente[]
     */
    public function getCliente(): Collection
    {
        return $this->Cliente;
    }

    public function addCliente(Cliente $cliente): self
    {
        if (!$this->Cliente->contains($cliente)) {
            $this->Cliente[] = $cliente;
            $cliente->setUsuario($this);
        }

        return $this;
    }

    public function removeCliente(Cliente $cliente): self
    {
        if ($this->Cliente->contains($cliente)) {
            $this->Cliente->removeElement($cliente);
            // set the owning side to null (unless already changed)
            if ($cliente->getUsuario() === $this) {
                $cliente->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Proveedor[]
     */
    public function getProveedores(): Collection
    {
        return $this->Proveedores;
    }

    public function addProveedore(Proveedor $proveedore): self
    {
        if (!$this->Proveedores->contains($proveedore)) {
            $this->Proveedores[] = $proveedore;
            $proveedore->setUsuario($this);
        }

        return $this;
    }

    public function removeProveedore(Proveedor $proveedore): self
    {
        if ($this->Proveedores->contains($proveedore)) {
            $this->Proveedores->removeElement($proveedore);
            // set the owning side to null (unless already changed)
            if ($proveedore->getUsuario() === $this) {
                $proveedore->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Producto[]
     */
    public function getProductos(): Collection
    {
        return $this->Productos;
    }

    public function addProducto(Producto $producto): self
    {
        if (!$this->Productos->contains($producto)) {
            $this->Productos[] = $producto;
            $producto->setUsuario($this);
        }

        return $this;
    }

    public function removeProducto(Producto $producto): self
    {
        if ($this->Productos->contains($producto)) {
            $this->Productos->removeElement($producto);
            // set the owning side to null (unless already changed)
            if ($producto->getUsuario() === $this) {
                $producto->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Historial[]
     */
    public function getHistoriales(): Collection
    {
        return $this->Historiales;
    }

    public function addHistoriale(Historial $historiale): self
    {
        if (!$this->Historiales->contains($historiale)) {
            $this->Historiales[] = $historiale;
            $historiale->setUsuario($this);
        }

        return $this;
    }

    public function removeHistoriale(Historial $historiale): self
    {
        if ($this->Historiales->contains($historiale)) {
            $this->Historiales->removeElement($historiale);
            // set the owning side to null (unless already changed)
            if ($historiale->getUsuario() === $this) {
                $historiale->setUsuario(null);
            }
        }

        return $this;
    }
}
