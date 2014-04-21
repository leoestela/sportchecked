<?php

namespace SportChecked\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Contact
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SportChecked\ContactBundle\Entity\ContactRepository")
 */
class Contact {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\UserBundle\Entity\User")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\UserBundle\Entity\User", inversedBy="contacts")
     * @ORM\JoinColumn(name="contact", referencedColumnName="id")
     */
    protected $contact;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starts", type="datetime")
     */
    protected $starts;

    /**
     * @ORM\OneToMany(targetEntity="SportChecked\ContactBundle\Entity\ListMember", mappedBy="contact")
     */
    private $lists;

    /**
     * Set default values
     *
     * @return Contact
     */
    public function __construct() {
        $this->starts = new \DateTime();
        $this->lists = new ArrayCollection();
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
     * @return Contact
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
     * Set contact
     *
     * @param string $contact
     * @return Contact
     */
    public function setContact(\SportChecked\UserBundle\Entity\User $contact = null) {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string 
     */
    public function getContact() {
        return $this->contact;
    }

    /**
     * Set starts
     *
     * @param \DateTime $starts
     * @return Contact
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

    /**
     * Set lists
     *
     * @param integer $lists
     */
    public function setLists(\SportChecked\ContactBundle\Entity\ListMember $list) {
        $this->lists[] = $list;
    }

    /**
     * Get lists
     *
     * @return array 
     */
    public function getLists() {
        return $this->lists;
    }

    /**
     * Add lists
     *
     * @param \SportChecked\ContactBundle\Entity\ListMember $lists
     * @return Contact
     */
    public function addLists(\SportChecked\ContactBundle\Entity\ListMember $list) {
        $this->lists[] = $list;

        return $this;
    }

    /**
     * Remove lists
     *
     * @param \SportChecked\ContactBundle\Entity\ListMember $lists
     */
    public function removeLists(\SportChecked\ContactBundle\Entity\ListMember $list) {
        $this->contacts->removeElement($list);
    }

}