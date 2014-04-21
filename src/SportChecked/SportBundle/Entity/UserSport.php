<?php

namespace SportChecked\SportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSport
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SportChecked\SportBundle\Entity\SportRepository")
 */
class UserSport {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\UserBundle\Entity\User", inversedBy="sports")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\SportBundle\Entity\Sport", inversedBy="followers")
     * @ORM\JoinColumn(name="sport_id", referencedColumnName="id")
     */
    protected $sport;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starts", type="datetime")
     */
    protected $starts;

    /**
     * Set default values
     *
     * @return UserSport
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
     * @return UserSport
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
     * Set sport
     *
     * @param string $sport
     * @return UserSport
     */
    public function setSport(\SportChecked\SportBundle\Entity\Sport $sport = null) {
        $this->sport = $sport;

        return $this;
    }

    /**
     * Get sport
     *
     * @return string 
     */
    public function getSport() {
        return $this->sport;
    }

    /**
     * Set starts
     *
     * @param \DateTime $starts
     * @return UserSport
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