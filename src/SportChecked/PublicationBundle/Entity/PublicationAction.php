<?php

namespace SportChecked\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PublicationAction
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SportChecked\PublicationBundle\Entity\PublicationRepository")
 */
class PublicationAction {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\PublicationBundle\Entity\Publication", inversedBy="actions")
     * @ORM\JoinColumn(name="publication_id", referencedColumnName="id")
     */
    private $publication;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\BoardBundle\Entity\Board")
     */
    private $board;

    /**
     * @ORM\ManyToOne(targetEntity="SportChecked\CategoryBundle\Entity\Category")
     */
    private $category;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published", type="datetime", nullable=true)
     */
    private $published;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="shared", type="datetime", nullable=true)
     */
    private $shared;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="saved", type="datetime", nullable=true)
     */
    private $saved;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastaction", type="datetime")
     */
    private $lastaction;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set publication
     *
     * @param string $publication
     * @return PublicationAction
     */
    public function setPublication(\SportChecked\PublicationBundle\Entity\Publication $publication = null) {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return string 
     */
    public function getPublication() {
        return $this->publication;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return PublicationAction
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
     * Set board
     *
     * @param string $board
     * @return PublicationAction
     */
    public function setBoard(\SportChecked\BoardBundle\Entity\Board $board) {
        $this->board = $board;

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
     * Set category
     *
     * @param string $category
     * @return PublicationAction
     */
    public function setCategory(\SportChecked\CategoryBundle\Entity\Category $category) {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Delete category
     *
     * @return PublicationAction
     */
    public function deleteCategory() {
        $this->category = null;

        return $this;
    }
    
    /**
     * Set published
     *
     * @param \DateTime $published
     * @return PublicationAction
     */
    public function setPublished($published) {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return \DateTime 
     */
    public function getPublished() {
        return $this->published;
    }

    /**
     * Set shared
     *
     * @param \DateTime $shared
     * @return PublicationAction
     */
    public function setShared($shared) {
        $this->shared = $shared;

        return $this;
    }

    /**
     * Get shared
     *
     * @return \DateTime 
     */
    public function getShared() {
        return $this->shared;
    }

    /**
     * Set saved
     *
     * @param \DateTime $saved
     * @return PublicationAction
     */
    public function setSaved($saved) {
        $this->saved = $saved;

        return $this;
    }

    /**
     * Get saved
     *
     * @return \DateTime 
     */
    public function getSaved() {
        return $this->saved;
    }

    /**
     * Set lastaction
     *
     * @param \DateTime $lastaction
     * @return PublicationAction
     */
    public function setLastaction($lastaction) {
        $this->lastaction = $lastaction;

        return $this;
    }

    /**
     * Get lastaction
     *
     * @return \DateTime 
     */
    public function getLastaction() {
        return $this->lastaction;
    }

}