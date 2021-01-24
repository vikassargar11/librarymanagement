<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookIssue
 *
 * @ORM\Table(name="book_issue")
 * @ORM\Entity
 */
class BookIssue
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", length=10, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    public $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="book_id", type="integer", nullable=false)
     */
    public $bookId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="book_issue_date", type="date", nullable = false)
     */
    public $bookIssueDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="book_return_date", type="date", nullable = true)
     */
    public $bookReturnDate;
    
    /**
     * @var string
     *
     * @ORM\Column(name="paid_rent", type="decimal", precision=4, scale=2, nullable=false)
     */
    public $paidRent = '0.00';
}

