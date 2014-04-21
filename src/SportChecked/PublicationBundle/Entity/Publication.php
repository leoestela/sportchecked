<?php

namespace SportChecked\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Publication
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SportChecked\PublicationBundle\Entity\PublicationRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Publication {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\SportBundle\Entity\Sport")
     */
    private $sport;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\FeatureBundle\Entity\Feature")
     */
    protected $modality;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\FeatureBundle\Entity\Feature")
     */
    protected $media;
    
    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\BoardBundle\Entity\Board")
     */
    private $board;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=true)
     */
    private $subtitle;

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
     * @ORM\Column(name="body", type="string", length=500, nullable=true)
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=1000, nullable=true)
     */
    private $link;

    /**
     * @ORM\OneToMany(targetEntity="SportChecked\PublicationBundle\Entity\PublicationAction", mappedBy="publication")
     */
    private $actions;

    /**
     * @ORM\OneToMany(targetEntity="SportChecked\PublicationBundle\Entity\PublicationComment", mappedBy="publication", cascade={"all"})
     */
    private $comments;

    /**
     * @var integer
     *
     * @ORM\Column(name="nshares", type="integer")
     */
    private $nshares;

    /**
     * @var integer
     *
     * @ORM\Column(name="nsaves", type="integer")
     */
    private $nsaves;

    /**
     * @var integer
     *
     * @ORM\Column(name="ncomments", type="integer")
     */
    private $ncomments;

    /**
     * Set default values
     *
     * @return Publication
     */
    public function __construct() {
        $this->created = new \DateTime();
        $this->nshares = 0;
        $this->nsaves = 0;
        $this->ncomments = 0;
        $this->actions = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * Publication to string
     *
     * @return string 
     */
    public function __toString() {
        return $this->getTitle();
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
     * Set sport
     *
     * @param string $sport
     * @return Publication
     */
    public function setSport(\SportChecked\SportBundle\Entity\Sport $sport) {
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
     * Set user
     *
     * @param string $user
     * @return Publication
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
     * Set modality
     *
     * @param string $modality
     * @return Publication
     */
    public function setModality($modality) {
        $this->modality = $modality;

        return $this;
    }

    /**
     * Get modality
     *
     * @return string 
     */
    public function getModality() {
        return $this->modality;
    }

    /**
     * Set board
     *
     * @param string $board
     * @return Publication
     */
    public function setBoard(\SportChecked\BoardBundle\Entity\Board $board = null) {
        $this->board = $board;

        return $this;
    }

    /**
     * Null board
     *
     * @param string $board
     * @return Publication
     */
    public function nullBoard() {
        $this->board = null;

        return $this;
    }
    
    /**
     * Get board
     *
     * @return string 
     */
    public function getBoard() {
        return $this->board;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Publication
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
     * Set title
     *
     * @param string $title
     * @return Publication
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     * @return Publication
     */
    public function setSubtitle($subtitle) {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle() {
        return $this->subtitle;
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return Publication
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
        return 'uploads/publication/images';
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
     * Set body
     *
     * @param string $body
     * @return Publication
     */
    public function setBody($body) {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Publication
     */
    public function setLink($link) {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * Set actions
     *
     * @param integer $actions
     */
    public function setActions(\SportChecked\PublicationBundle\Entity\PublicationAction $publicationAction) {
        $this->actions[] = $publicationAction;
    }

    /**
     * Get actions
     *
     * @return array 
     */
    public function getActions() {
        return $this->actions;
    }

    /**
     * Add actions
     *
     * @param \SportChecked\PublicationBundle\Entity\PublicationAction $actions
     * @return Publication
     */
    public function addActions(\SportChecked\PublicationBundle\Entity\PublicationAction $action) {
        $this->actions[] = $action;

        return $this;
    }

    /**
     * Remove actions
     *
     * @param \SportChecked\PublicationBundle\Entity\PublicationAction $actions
     */
    public function removeActions(\SportChecked\PublicationBundle\Entity\PublicationAction $action) {
        $this->actions->removeElement($action);
    }

    /**
     * Set comments
     *
     * @param integer $comments
     */
    public function setComments(PublicationComment $publicationComment) {
        $this->comments[] = $publicationComment;
    }

    /**
     * Get comments
     *
     * @return array 
     */
    public function getComments() {
        return $this->comments;
    }

    /**
     * Add comments
     *
     * @param \SportChecked\PublicationBundle\Entity\PublicationComment $comments
     * @return Publication
     */
    public function addComments(\SportChecked\PublicationBundle\Entity\PublicationComment $comment) {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \SportChecked\PublicationBundle\Entity\PublicationComment $comments
     */
    public function removeComments(\SportChecked\PublicationBundle\Entity\PublicationComment $comment) {
        $this->comments->removeElement($comment);
    }

    /**
     * Set nshares
     *
     * @param integer $nshares
     * @return Publication
     */
    public function setNShares($nshares) {
        $this->nshares = $nshares;

        return $this;
    }

    /**
     * Increase nshares
     *
     * @param integer $nshares
     * @return Publication
     */
    public function increaseNShares() {
        $this->nshares = $this->nshares + 1;

        return $this;
    }

    /**
     * Decrease nshares
     *
     * @param integer $nshares
     * @return Publication
     */
    public function decreaseNShares() {
        $this->nshares = $this->nshares - 1;

        return $this;
    }

    /**
     * Get nshares
     *
     * @return integer 
     */
    public function getNShares() {
        return $this->nshares;
    }

    /**
     * Set nsaves
     *
     * @param integer $nsaves
     * @return Publication
     */
    public function setNSaves($nsaves) {
        $this->nsaves = $nsaves;

        return $this;
    }

    /**
     * Increase nsaves
     *
     * @param integer $nsaves
     * @return Publication
     */
    public function increaseNSaves() {
        $this->nsaves = $this->nsaves + 1;

        return $this;
    }

    /**
     * Decrease nsaves
     *
     * @param integer $nsaves
     * @return Publication
     */
    public function decreaseNSaves() {
        $this->nsaves = $this->nsaves - 1;

        return $this;
    }

    /**
     * Get nsaves
     *
     * @return integer 
     */
    public function getNSaves() {
        return $this->nsaves;
    }

    /**
     * Set ncomments
     *
     * @param integer $ncomments
     * @return Publication
     */
    public function setNComments($ncomments) {
        $this->ncomments = $ncomments;

        return $this;
    }

    /**
     * Increase ncomments
     *
     * @param integer $ncomments
     * @return Publication
     */
    public function increaseNComments() {
        $this->ncomments = $this->ncomments + 1;

        return $this;
    }

    /**
     * Decrease ncomments
     *
     * @param integer $ncomments
     * @return Publication
     */
    public function decreaseNComments() {
        $this->ncomments = $this->ncomments - 1;

        return $this;
    }

    /**
     * Get ncomments
     *
     * @return integer 
     */
    public function getNComments() {
        return $this->ncomments;
    }

}