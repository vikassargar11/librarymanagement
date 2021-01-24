<?php
namespace Application\Service;

use Application\Entity\Book;
use Application\Entity\User;
use Application\Entity\BookIssue;

class BookManager 
{
    /**
    * Doctrine entity manager.
    * @var Doctrine\ORM\EntityManager
    */
    private $entityManager;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    } 

    /**
     * Book issue
     * 
     * @param type $bookIssueData
     */
    public function bookIssue($bookIssueData)
    {
        $bookIssue = new BookIssue();
        $bookIssue->userId = $bookIssueData['user_id'];
        $bookIssue->bookId = $bookIssueData['book_id'];
       
        $bookIssue->bookIssueDate = new \DateTime();
        $bookIssue->bookReturnDate = NULL;
        $bookIssue->paidRent = 0.00;
        $this->entityManager->persist($bookIssue);
        $this->entityManager->flush();
    }
    
    /**
     * Book return
     * 
     * @param type $bookReturnData
     */
    public function bookReturn($bookReturnData)
    {
        $amount = $this->getPaidRent($bookReturnData);
        
        $updateQueryBuilder = $this->entityManager->createQueryBuilder();
        $update = $updateQueryBuilder->update(BookIssue::class, 'bi')
                ->set('bi.bookReturnDate', '?1')
                ->set('bi.paidRent', '?4')
                ->where('bi.userId = ?2')
                ->andWhere('bi.bookId = ?3')
                ->andWhere('bi.bookReturnDate IS NULL')
                ->setParameter(1, new \DateTime())
                ->setParameter(2, $bookReturnData['user_id'])
                ->setParameter(3, $bookReturnData['book_id'])
                ->setParameter(4, $amount)
                ->getQuery();
        $update->execute();
        $this->entityManager->flush();
    }
    
    /**
     * Total rent Paid
     */
    public function getTotalRent()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select("SUM(bi.paidRent) as totalRentPaid")
                ->from(BookIssue::class, 'bi')
                ->where('bi.bookReturnDate IS NOT NULL');

        $result = $queryBuilder->getQuery()->getOneOrNullResult();
        
        return isset($result['totalRentPaid']) ? $result['totalRentPaid'] : 0;
    }
    
    /**
     * Get rent paid
     * 
     * @param type $bookReturnData
     * @return type
     */
    public function getPaidRent($bookReturnData)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('bi.bookIssueDate')
            ->from(BookIssue::class, 'bi')
            ->where('bi.userId = ?1')
            ->andWhere('bi.bookId = ?2')
            ->andWhere('bi.bookReturnDate IS NULL')
            ->setParameter(1, $bookReturnData['user_id'])
            ->setParameter(2, $bookReturnData['book_id']);

        $result = $queryBuilder->getQuery()->getOneOrNullResult();
        
        $issueDate = $result['bookIssueDate']->format('d-m-Y');
        $currentDate = date("d-m-Y");
        
        $diff = date_diff(date_create($issueDate), date_create($currentDate));
       
       $amount =  ($diff->format("%a") + 1) * 2;
       
       return $amount;
    }
    
    /**
     * Book issue on that day
     * 
     * @param type $dateData
     * @return int
     */
    public function bookIssuedOnDay($dateData)
    {
        $issuedBookDetail = [];
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('b.id', 'b.name', 'u.username as personName')
            ->from(BookIssue::class, 'bi')
            ->join(Book::class, 'b', 'with', 
                    'b.id = bi.bookId'
              )
            ->join(User::class, 'u', 'with', 
                    'u.userId = bi.userId'
              )     
            ->where('bi.bookIssueDate = ?1')            
            ->setParameter('1', date_create($dateData));

        $result = $queryBuilder->getQuery()->getResult();
        
        
        foreach ($result as $key => $value) {
            $issuedBookDetail[$key] = $value;
            $issuedBookDetail[$key]['quantity'] = 1;
        }
       
       return $issuedBookDetail;
    }
    
    /**
     * Book returned on that day
     * 
     * @param type $dateData
     * @return int
     */
    public function bookReturnedOnDay($dateData)
    {
        
        $returnedBookDetail = [];
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('b.id', 'b.name', 'u.username as personName')
            ->from(BookIssue::class, 'bi')
            ->join(Book::class, 'b', 'with', 
                    'b.id = bi.bookId'
              )
            ->join(User::class, 'u', 'with', 
                    'u.userId = bi.userId'
              )     
            ->where('bi.bookReturnDate = ?1')            
            ->setParameter('1', date_create($dateData));

        $result = $queryBuilder->getQuery()->getResult();
        
        foreach ($result as $key => $value) {
            $returnedBookDetail[$key] = $value;
            $returnedBookDetail[$key]['quantity'] = 1;
        }
       
       return $returnedBookDetail;
    }
    
    /**
     * Total Charge on the day
     * 
     * @param type $dateData
     * @return type
     */
     public function totalChargeOnDay($dateData)
    {
        $returnedBookDetail = [];
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('SUM(bi.paidRent) as totalRentPaid')
            ->from(BookIssue::class, 'bi')
            ->where('bi.bookReturnDate = ?1')            
            ->setParameter('1', date_create($dateData));

        $result = $queryBuilder->getQuery()->getOneOrNullResult();
        
       
       return !empty($result['totalRentPaid']) ? $result['totalRentPaid'] : 0;
    }
    
    /**
     * Number of book issued
     * 
     * @param type $bookIssueData
     * @return type
     */
    public function numberOfBookIssued($bookIssueData)
    {
        
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('bi.id')
            ->from(BookIssue::class, 'bi')
            
            ->where('bi.userId = ?1')
            ->andWhere('bi.bookReturnDate IS NULL')    
            ->setParameter('1', $bookIssueData['user_id']);

        $result = $queryBuilder->getQuery()->getResult();
        
        return $result;
    }
}