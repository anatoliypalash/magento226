<?php

namespace PalashAs\AskQuestion\Api\Data;

/**
 * Ask a Question interface.
 * @api
 */
interface AskQuestionInterface
{
    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Set ID
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * Gets the created-at timestamp for the ask a question.
     *
     * @return string|null Created-at timestamp.
     */
    public function getCreatedAt(): ?string;

    /**
     * Gets the updated-at timestamp for the ask a question.
     *
     * @return string|null Updated-at timestamp.
     */
    public function getUpdatedAt(): ?string;

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * Set email
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email);

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone(): string;

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone);

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Set sku
     *
     * @param string $sku
     *
     * @return $this
     */
    public function setSku($sku);

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion(): string;

    /**
     * Set question
     *
     * @param string $question
     *
     * @return $this
     */
    public function setQuestion($question);

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus(): ?string;

    /**
     * Set status
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get store ID
     *
     * @return string
     */
    public function getStoreId();

    /**
     * Set store ID
     *
     * @param int $storeId
     *
     * @return $this
     */
    public function setStoreId($storeId);

}
