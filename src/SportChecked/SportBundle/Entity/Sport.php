<?php

namespace SportChecked\SportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SportChecked\PublicationBundle\Util\Util;

/**
 * Sport
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SportChecked\SportBundle\Entity\SportRepository")
 */
class Sport {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255)
     */
    protected $icon;

    /**
     * @ORM\OneToMany(targetEntity="SportChecked\SportBundle\Entity\UserSport", mappedBy="sport")
     */
    private $followers;

    /**
     * @var integer
     *
     * @ORM\Column(name="nfollowers", type="integer")
     */
    protected $nfollowers;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;

    /**
     * Set default values
     *
     * @return Sport
     */
    public function __construct() {
        $this->created = new \DateTime();
        $this->nfollowers = 0;
        $this->followers = new ArrayCollection();
    }

    /**
     * Sport to string
     *
     * @return string 
     */
    public function __toString() {
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     * @return Sport
     */
    public function setName($name) {
        $this->name = $name;
        $this->slug = Util::getSlug($name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Sport
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return Sport
     */
    public function setIcon($icon) {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * Set followers
     *
     * @param integer $followers
     */
    public function setFollowers(\SportChecked\SportBundle\Entity\UserSport $follower) {
        $this->followers[] = $follower;
    }

    /**
     * Get followers
     *
     * @return array 
     */
    public function getFollowers() {
        return $this->followers;
    }

    /**
     * Add followers
     *
     * @param \SportChecked\SportBundle\Entity\UserSport $followers
     * @return Sport
     */
    public function addFollowers(\SportChecked\SportBundle\Entity\UserSport $follower) {
        $this->followers[] = $follower;

        return $this;
    }

    /**
     * Remove followers
     *
     * @param \SportChecked\SportBundle\Entity\UserSport $followers
     */
    public function removeFollowers(\SportChecked\SportBundle\Entity\UserSport $follower) {
        $this->followers->removeElement($follower);
    }

    /**
     * Set nfollowers
     *
     * @param integer $nfollowers
     * @return Sport
     */
    public function setNFollowers($nfollowers) {
        $this->nfollowers = $nfollowers;

        return $this;
    }

    /**
     * Get nfollowers
     *
     * @return integer 
     */
    public function getNFollowers() {
        return $this->nfollowers;
    }

    /**
     * Increase nfollowers
     *
     * @param integer $nfollowers
     * @return Sport
     */
    public function increaseNFollowers() {
        $this->nfollowers = $this->nfollowers + 1;

        return $this;
    }

    /**
     * Decrease nfollowers
     *
     * @param integer $nfollowers
     * @return Sport
     */
    public function decreaseNFollowers() {
        $this->nfollowers = $this->nfollowers - 1;

        return $this;
    }
    
    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Sport
     */
    public function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * Set removed
     *
     * @param \DateTime $removed
     * @return Sport
     */
    public function setRemoved($removed) {
        $this->removed = $removed;

        return $this;
    }

    /**
     * Get removed
     *
     * @return \DateTime 
     */
    public function getRemoved() {
        return $this->removed;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Sport
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug() {
        return $this->slug;
    }

}