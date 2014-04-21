<?php

namespace SportChecked\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ListMember
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SportChecked\ContactBundle\Entity\ContactRepository")
 */
class ListMember
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\ContactBundle\Entity\ContactList", inversedBy="members")
     * @ORM\JoinColumn(name="contactlist", referencedColumnName="id")
     */
    private $contactlist;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\ContactBundle\Entity\Contact", inversedBy="lists")
     * @ORM\JoinColumn(name="contact", referencedColumnName="id")
     */
    private $contact;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starts", type="datetime")
     */
    protected $starts;
    

    /**
     * Set default values
     *
     * @return ListMember
     */
    public function __construct() {
        $this->starts = new \DateTime();
    }
    
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
     * Set contactlist
     *
     * @param string $contactlist
     * @return ListMember
     */
    public function setContactList(\SportChecked\ContactBundle\Entity\ContactList $contactlist = null) {
        $this->contactlist = $contactlist;

        return $this;
    }

    /**
     * Get contactlist
     *
     * @return string 
     */
    public function getContactList() {
        return $this->contactlist;
    }

    /**
     * Set contact
     *
     * @param string $contact
     * @return ListMember
     */
    public function setContact(\SportChecked\ContactBundle\Entity\Contact $contact = null)
    {
        $this->contact = $contact;
    
        return $this;
    }

    /**
     * Get contact
     *
     * @return string 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set starts
     *
     * @param \DateTime $starts
     * @return ListMember
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