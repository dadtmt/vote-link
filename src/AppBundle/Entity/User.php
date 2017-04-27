<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
    * @Assert\Length(
    *     min=8,
    *     max=100,
    *     minMessage="mot de passe trop court ",
    *     groups={"Profile", "ResetPassword", "Registration", "ChangePassword"}
    * )
    * @Assert\Regex(
    *     pattern="/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,100}$/",
    *     message="Le mot de passe doit contenir des caractères aA-zZ et nombres 0-9",
    *     groups={"Profile", "ResetPassword", "Registration", "ChangePassword"}
    * )
    */
   protected $plainPassword;
}
