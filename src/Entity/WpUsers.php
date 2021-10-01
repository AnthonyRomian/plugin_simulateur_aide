<?php

namespace App\Entity;

use App\Repository\WpUsersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WpUsersRepository::class)
 * @ORM\Table(name="wp_users")
 */
class WpUsers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column
     */
    private $user_email;

    /**
     * @ORM\Column
     */
    private $display_name;


    public function getUserEmail(): ?string
    {
        return $this->user_email;
    }

    public function getDisplayName(): ?string
    {
        return $this->display_name;
    }
}
