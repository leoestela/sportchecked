<?php

namespace SportChecked\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use SportChecked\PublicationBundle\Util\Util;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SportChecked\UserBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=500)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=500)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

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
     * @var string
     *
     * @ORM\Column(name="intro", type="string", length=500, nullable=true)
     */
    private $intro;

    /**
     * @var string
     *
     * @ORM\Column(name="ubication", type="string", length=500, nullable=true)
     */
    private $ubication;

    /**
     * @var string
     *
     * @ORM\Column(name="web", type="string", length=500, nullable=true)
     */
    private $web;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\FeatureBundle\Entity\Feature")
     */
    private $gender;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\FeatureBundle\Entity\Country")
     */
    private $country;

    /**
     * @var integer
     *
     * @ORM\Column(name="npublications", type="integer")
     */
    private $npublications;

    /**
     * @var integer
     *
     * @ORM\Column(name="nfolders", type="integer")
     */
    private $nfolders;

    /**
     * @var integer
     *
     * @ORM\Column(name="nsports", type="integer")
     */
    private $nsports;

    /**
     * @var integer
     *
     * @ORM\Column(name="nfollowers", type="integer")
     */
    private $nfollowers;

    /**
     * @var integer
     *
     * @ORM\Column(name="nfollowing", type="integer")
     */
    private $nfollowing;

    /**
     * @var integer
     *
     * @ORM\Column(name="nlists", type="integer")
     */
    private $nlists;

    /**
     * @ORM\OneToMany(targetEntity="SportChecked\ContactBundle\Entity\Contact", mappedBy="contact")
     */
    private $contacts;

    /**
     * @ORM\OneToMany(targetEntity="SportChecked\FollowerBundle\Entity\Follower", mappedBy="user")
     */
    private $followers;

    /**
     * @ORM\OneToMany(targetEntity="SportChecked\SportBundle\Entity\UserSport", mappedBy="user")
     */
    private $sports;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * Set default values
     *
     * @return User
     */
    public function __construct() {
        $this->created = new \DateTime();
        $this->npublications = 0;
        $this->nfolders = 0;
        $this->nsports = 0;
        $this->nfollowers = 0;
        $this->nfollowing = 0;
        $this->nlists = 0;
        $this->contacts = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->sports = new ArrayCollection();
    }

    /**
     * User to string
     *
     * @return string 
     */
    public function __toString() {
        return $this->getUsername();
    }

    /**
     * Provider functions
     */
    function equals(\Symfony\Component\Security\Core\User\UserInterface $user) {
        return $this->getSlug() == $user->getSlug();
    }

    function eraseCredentials() {
        
    }

    function getRoles() {
        return array('ROLE_USER');
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;
        $this->slug = Util::getSlug($username);

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return Board
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
        return 'uploads/user/images';
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
     * Set intro
     *
     * @param string $intro
     * @return User
     */
    public function setIntro($intro) {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Get intro
     *
     * @return string 
     */
    public function getIntro() {
        return $this->intro;
    }

    /**
     * Set ubication
     *
     * @param string $ubication
     * @return User
     */
    public function setUbication($ubication) {
        $this->ubication = $ubication;

        return $this;
    }

    /**
     * Get ubication
     *
     * @return string 
     */
    public function getUbication() {
        return $this->ubication;
    }

    /**
     * Set web
     *
     * @param string $web
     * @return User
     */
    public function setWeb($web) {
        $this->web = $web;

        return $this;
    }

    /**
     * Get web
     *
     * @return string 
     */
    public function getWeb() {
        return $this->web;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return User
     */
    public function setBirthdate($birthdate) {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate() {
        return $this->birthdate;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender(\SportChecked\FeatureBundle\Entity\Feature $gender) {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry(\SportChecked\FeatureBundle\Entity\Country $country) {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set npublications
     *
     * @param integer $npublications
     * @return User
     */
    public function setNPublications($npublications) {
        $this->npublications = $npublications;

        return $this;
    }

    /**
     * Get npublications
     *
     * @return integer 
     */
    public function getNPublications() {
        return $this->npublications;
    }

    /**
     * Increase npublications
     *
     * @param integer $npublications
     * @return User
     */
    public function increaseNPublications() {
        $this->npublications = $this->npublications + 1;

        return $this;
    }

    /**
     * Decrease npublications
     *
     * @param integer $npublications
     * @return User
     */
    public function decreaseNPublications() {
        $this->npublications = $this->npublications - 1;

        return $this;
    }
    
    /**
     * Abate npublications
     *
     * @param integer $npublications
     * @return User
     */
    public function abateNPublications($count) {
        $this->npublications = $this->npublications - $count;

        return $this;
    }    

    /**
     * Set nfolders
     *
     * @param integer $nfolders
     * @return User
     */
    public function setNFolders($nfolders) {
        $this->nfolders = $nfolders;

        return $this;
    }

    /**
     * Get nfolders
     *
     * @return integer 
     */
    public function getNFolders() {
        return $this->nfolders;
    }

    /**
     * Increase nfolders
     *
     * @param integer $nfolders
     * @return User
     */
    public function increaseNFolders() {
        $this->nfolders = $this->nfolders + 1;

        return $this;
    }

    /**
     * Decrease nfolders
     *
     * @param integer $nfolders
     * @return User
     */
    public function decreaseNFolders() {
        $this->nfolders = $this->nfolders - 1;

        return $this;
    }

    /**
     * Set nsports
     *
     * @param integer $nsports
     * @return User
     */
    public function setNSports($nsports) {
        $this->nsports = $nsports;

        return $this;
    }

    /**
     * Get nsports
     *
     * @return integer 
     */
    public function getNSports() {
        return $this->nsports;
    }

    /**
     * Increase nsports
     *
     * @param integer $nsports
     * @return User
     */
    public function increaseNSports() {
        $this->nsports = $this->nsports + 1;

        return $this;
    }

    /**
     * Decrease nsports
     *
     * @param integer $nsports
     * @return User
     */
    public function decreaseNSports() {
        $this->nsports = $this->nsports - 1;

        return $this;
    }

    /**
     * Set nfollowers
     *
     * @param integer $nfollowers
     * @return User
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
     * @return User
     */
    public function increaseNFollowers() {
        $this->nfollowers = $this->nfollowers + 1;

        return $this;
    }

    /**
     * Decrease nfollowers
     *
     * @param integer $nfollowers
     * @return User
     */
    public function decreaseNFollowers() {
        $this->nfollowers = $this->nfollowers - 1;

        return $this;
    }

    /**
     * Set nfollowing
     *
     * @param integer $nfollowing
     * @return User
     */
    public function setNFollowing($nfollowing) {
        $this->nfollowing = $nfollowing;

        return $this;
    }

    /**
     * Get nfollowing
     *
     * @return integer 
     */
    public function getNFollowing() {
        return $this->nfollowing;
    }

    /**
     * Increase nfollowing
     *
     * @param integer $nfollowing
     * @return User
     */
    public function increaseNFollowing() {
        $this->nfollowing = $this->nfollowing + 1;

        return $this;
    }

    /**
     * Decrease nfollowing
     *
     * @param integer $nfollowing
     * @return User
     */
    public function decreaseNFollowing() {
        $this->nfollowing = $this->nfollowing - 1;

        return $this;
    }

    /**
     * Set nlists
     *
     * @param integer $nlists
     * @return User
     */
    public function setNLists($nlists) {
        $this->nlists = $nlists;

        return $this;
    }

    /**
     * Get lists
     *
     * @return integer 
     */
    public function getNLists() {
        return $this->nlists;
    }

    /**
     * Increase nlists
     *
     * @param integer $nlists
     * @return User
     */
    public function increaseNLists() {
        $this->nlists = $this->nlists + 1;

        return $this;
    }

    /**
     * Decrease nlists
     *
     * @param integer $nlists
     * @return User
     */
    public function decreaseNLists() {
        $this->nlists = $this->nlists - 1;

        return $this;
    }

    /**
     * Set contacts
     *
     * @param integer $contacts
     */
    public function setContacts(\SportChecked\ContactBundle\Entity\Contact $contact) {
        $this->contacts[] = $contact;
    }

    /**
     * Get contacts
     *
     * @return array 
     */
    public function getContacts() {
        return $this->contacts;
    }

    /**
     * Add contacts
     *
     * @param \SportChecked\ContactBundle\Entity\Contact $contacts
     * @return User
     */
    public function addContacts(\SportChecked\ContactBundle\Entity\Contact $contact) {
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * Remove contacts
     *
     * @param \SportChecked\ContactBundle\Entity\Contact $contacts
     */
    public function removeContacts(\SportChecked\ContactBundle\Entity\Contact $contact) {
        $this->contacts->removeElement($contact);
    }

    /**
     * Set followers
     *
     * @param integer $followers
     */
    public function setFollowers(\SportChecked\FollowerBundle\Entity\Follower $follower) {
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
     * @param \SportChecked\FollowerBundle\Entity\Follower $followers
     * @return User
     */
    public function addFollowers(\SportChecked\FollowerBundle\Entity\Follower $follower) {
        $this->followers[] = $follower;

        return $this;
    }

    /**
     * Remove followers
     *
     * @param \SportChecked\FollowerBundle\Entity\Follower $followers
     */
    public function removeFollowers(\SportChecked\FollowerBundle\Entity\Follower $follower) {
        $this->followers->removeElement($follower);
    }

    /**
     * Set sports
     *
     * @param integer $sports
     */
    public function setSports(\SportChecked\SportBundle\Entity\UserSport $sport) {
        $this->sports[] = $sport;
    }

    /**
     * Get sports
     *
     * @return array 
     */
    public function getSports() {
        return $this->sports;
    }

    /**
     * Add sports
     *
     * @param \SportChecked\SportBundle\Entity\UserSport $sports
     * @return User
     */
    public function addSports(\SportChecked\SportBundle\Entity\UserSport $sport) {
        $this->sports[] = $sport;

        return $this;
    }

    /**
     * Remove sports
     *
     * @param \SportChecked\SportBundle\Entity\UserSport $sports
     */
    public function removeSports(\SportChecked\SportBundle\Entity\UserSport $sport) {
        $this->sports->removeElement($sport);
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return User
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
     * Set slug
     *
     * @param string $slug
     * @return User
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