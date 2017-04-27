<?php
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="User")
     */
    private $votes;

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

    public function __construct()
    {
      $this->votes = new ArrayCollection();
    }

    /**
     * Add vote
     *
     * @param \AppBundle\Entity\Vote $vote
     *
     * @return User
     */
    public function addVote(\AppBundle\Entity\Vote $vote)
    {
      $this->votes[] = $vote;
      return $this;
    }

    /**
     * Remove vote
     *
     * @param \AppBundle\Entity\Vote $vote
     */
    public function removeVote(\AppBundle\Entity\Vote $vote)
    {
      $this->votes->removeElement($vote);
    }

    /**
     * Get vote
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
      return $this->votes;
    }
}
