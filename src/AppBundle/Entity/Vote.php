<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="Vote")
 */
class Vote
{
  /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
  private $id;

  /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="Vote")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


  /**
     * @ORM\ManyToOne(targetEntity="Link", inversedBy="Vote")
     * @ORM\JoinColumn(name="link_id", referencedColumnName="id")
     */
    private $link;

    /**
       * @ORM\Column(name="voteLike", type="boolean", nullable=true)
       */
    private $voteLike;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set like
     *
     * @param boolean $like
     *
     * @return Vote
     */
    public function setVoteLike($voteLike)
    {
        $this->voteLike = $voteLike;

        return $this;
    }

    /**
     * Get like
     *
     * @return boolean
     */
    public function getVoteLike()
    {
        return $this->voteLike;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Vote
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set link
     *
     * @param \AppBundle\Entity\Link $link
     *
     * @return Vote
     */
    public function setLink(\AppBundle\Entity\Link $link = null)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return \AppBundle\Entity\Link
     */
    public function getLink()
    {
        return $this->link;
    }
}
