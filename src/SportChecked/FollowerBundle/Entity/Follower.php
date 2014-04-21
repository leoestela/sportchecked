<?php

namespace SportChecked\FollowerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Follower
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SportChecked\FollowerBundle\Entity\FollowerRepository")
 */
class Follower {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\UserBundle\Entity\User", inversedBy="followers")
     * @ORM\JoinColumn(name="user", referencedColumnName="id") 
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\UserBundle\Entity\User") 
     */
    protected $follower;    

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starts", type="datetime")
     */
    private $starts;

    /**
     * Set default values
     *
     * @return Follower
     */
    public function __construct() {
        $this->starts = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return Follower
     */
    public function setUser(\SportChecked\UserBundle\Entity\User $user) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set follower
     *
     * @param string $follower
     * @return Follower
     */
    public function setFollower(\SportChecked\UserBundle\Entity\User $follower = null) {
        $this->follower = $follower;

        return $this;
    }

    /**
     * Get follower
     *
     * @return string 
     */
    public function getFollower() {
        return $this->follower;
    }

    /**
     * Set starts
     *
     * @param \DateTime $starts
     * @return Follower
     */
    public function setStarts($starts) {
        $this->starts = $starts;

        return $this;
    }

    /**
     * Get starts
     *
     * @return \DateTime 
     */
    public function getStarts() {
        return $this->starts;
    }

}