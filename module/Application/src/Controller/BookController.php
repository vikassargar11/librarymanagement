<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class BookController extends AbstractActionController
{
    
    //Constructor method is used to inject dependencies to the controller.
    public function __construct($entityManager, $bookManager)
    {        
        $this->entityManager = $entityManager;
        $this->bookManager = $bookManager;
    }
    
    //Issue Book
    public function bookIssueAction()
    {
        $bookIssueData = $this->getRequest()->getPost()->toArray();
       
        $checkNumberOfBookIssued = $this->bookManager->numberOfBookIssued($bookIssueData);
        
        if($checkNumberOfBookIssued < 5) {
            $this->bookManager->bookIssue($bookIssueData);
            $message = "Book Issued";
        } else {
            $message = "You have cross limit of book issue. You have already take 5 books";
        }
       
        return new JsonModel([
            'status' => 'SUCCESS',
            'message'=> $message,
            
        ]);
    }
    
    //Return Book
    public function bookReturnAction()
    {
        $bookReturnData = $this->getRequest()->getPost()->toArray();
        
        $this->bookManager->bookReturn($bookReturnData);
        
        return new JsonModel([
            'status' => 'SUCCESS',
            'message'=>'Book Issued',
            
        ]);
    }
    
    //Print total rent to be paid. Rent of the book is Rs. 2/- per day for any book
    public function totalRentAction()
    {
        
        $totalRent = $this->bookManager->getTotalRent();
        
        return new JsonModel([ ['total_rent' => $totalRent]]); 
    }
    
    //Take date as an input and print list of all the books issued on the day (id, name, quantity, personName), 
    //all the books returned on that day (id, name, quantity, personName, amountCharged), 
    //total amount charged on that day
    public function reportAction()
    {
        $dateData = $this->params()->fromRoute('reportdate', 0);
        
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $dateData)) {
            
            $issueRecord = $this->bookManager->bookIssuedOnDay($dateData);
            $returnRecord = $this->bookManager->bookReturnedOnDay($dateData);
            $total = $this->bookManager->totalChargeOnDay($dateData);

            return new JsonModel([
                'status' => 'SUCCESS',
                'message'=>'Report data',
                'data' => [
                    'BookIssueRecord' => $issueRecord,
                    'BookReturnRecord' => $returnRecord,
                    'TotalChargedAmount' => $total
                ]
            ]);
        } else {
            return new JsonModel([
                'status' => 'SUCCESS',
                'message'=> 'Date was not  correct. Date format was not correct YYYY-mm-dd',               
            ]);
        }
        
        
    }
}
