<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $intitule = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: ModuleSession::class, orphanRemoval: true)]
    private Collection $moduleSessions;

    public function __construct()
    {
        $this->moduleSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * @return Collection<int, ModuleSession>
     */
    public function getModuleSessions(): Collection
    {
        return $this->moduleSessions;
    }

    public function addModuleSession(ModuleSession $moduleSession): self
    {
        if (!$this->moduleSessions->contains($moduleSession)) {
            $this->moduleSessions->add($moduleSession);
            $moduleSession->setCategorie($this);
        }

        return $this;
    }

    public function removeModuleSession(ModuleSession $moduleSession): self
    {
        if ($this->moduleSessions->removeElement($moduleSession)) {
            // set the owning side to null (unless already changed)
            if ($moduleSession->getCategorie() === $this) {
                $moduleSession->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->intitule;
    }
}
