<?php
namespace App\Entity\Traite;
use Doctrine\ORM\Mapping as ORM;
trait createdAtTraite
{   #[ORM\Column (options:['default'=>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;
    
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }
    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }
}
?>
