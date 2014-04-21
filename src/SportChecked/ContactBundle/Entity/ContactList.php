<?php

namespace SportChecked\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * ContactList
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SportChecked\ContactBundle\Entity\ContactRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ContactList {

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
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    private $temp;

    /**
     * @ORM\OneToMany(targetEntity="SportChecked\ContactBundle\Entity\ListMember", mappedBy="contactlist", cascade={"remove"})
     */
    private $members;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $nmembers;   
    
    /**
     * Set default values
     *
     * @return ContactList
     */
    public function __construct() {
        $this->members = new ArrayCollection();
        $this->nmembers = 0;
    }

    /**
     * ContactList to string
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
     * Set user
     *
     * @param string $user
     * @return ContactList
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
     * Set name
     *
     * @param string $name
     * @return ContactList
     */
    public function setName($name) {
        $this->name = $name;

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
     * @return ContactList
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
     * Set photo
     *
     * @param string $photo
     * @return ContactList
     */
    public function setPhoto($photo) {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }

    public function getAbsolutePath() {
        return null === $this->photo ? null : $this->getUploadRootDir() . '/' . $this->photo;
    }

    public function getWebPath() {
        return null === $this->photo ? null : $this->getUploadDir() . '/' . $this->photo;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/contact/contactlist/images';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->photo)) {
            // store the old name to delete after the update
            $this->temp = $this->photo;
            $this->photo = null;
        } else {
            $this->photo = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->photo = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->photo);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Set members
     *
     * @param integer $members
     */
    public function setMembers(\SportChecked\ContactBundle\Entity\ListMember $member) {
        $this->members[] = $member;
    }

    /**
     * Get members
     *
     * @return array 
     */
    public function getMembers() {
        return $this->members;
    }

    /**
     * Add members
     *
     * @param \SportChecked\ContactBundle\Entity\ListMember $members
     * @return ContactList
     */
    public function addMembers(\SportChecked\ContactBundle\Entity\ListMember $member) {
        $this->members[] = $member;

        return $this;
    }

    /**
     * Remove members
     *
     * @param \SportChecked\ContactBundle\Entity\ListMember $members
     */
    public function removeMembers(\SportChecked\ContactBundle\Entity\ListMember $member) {
        $this->members->removeElement($member);
    }

    /**
     * Set nmembers
     *
     * @param integer $nmembers
     * @return ContactList
     */
    public function setNMembers($nmembers)
    {
        $this->nmembers = $nmembers;
    
        return $this;
    }

    /**
     * Get nmembers
     *
     * @return integer 
     */
    public function getNMembers()
    {
        return $this->nmembers;
    } 

    /**
     * Increase nmembers
     *
     * @param integer $nmembers
     * @return ContactList
     */
    public function increaseNMembers() {
        $this->nmembers = $this->nmembers + 1;

        return $this;
    }

    /**
     * Decrease nmembers
     *
     * @param integer $nmembers
     * @return ContactList
     */
    public function decreaseNMembers() {
        $this->nmembers = $this->nmembers - 1;

        return $this;
    }    
}