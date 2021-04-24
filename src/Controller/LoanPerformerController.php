<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\CustomerLoanDetails;


/**
 * LoanPerformer Controller
 *
 * @property \App\Model\Table\LoanPerformerTable $LoanPerformer
 */
class LoanPerformerController extends AppController
{
    protected $url = "http://104.211.10.181:8080/webservice/foxservice?wsdl";

    public function getFunctions(){
        $soapClient = new \SoapClient($this->url);
        var_dump($soapClient->__getFunctions());
    }
    
    protected function __convertXMLToArray($xmlData){
        
        return json_decode(json_encode(simplexml_load_string($xmlData)), 1);
    }

    public function getLoanPerformerVariables(){
        
        $loanNumber = "KO/000647";

        $loanDetails = new CustomerLoanDetails();
        $loanDetails->setClientCode("KO/G/000109");
        $soapClient = new \SoapClient($this->url);

//        $access = $soapClient->GetAccess([
//            'username' => 'Link_ko',
//            'password' => 'L@29LK_nzRmFU'
//        ]);
        
        $response = $soapClient->ActiveLoanStatus([
            'clientCode' => $loanDetails->getClientCode()
        ]);
//        var_dump($response->ActiveLoanStatusResult);
        
        $activeLoanStatusData = json_decode(json_encode(simplexml_load_string($response->ActiveLoanStatusResult)), 1);
        if(is_array($activeLoanStatusData)){
            $loanDetails->setLoanNumber($activeLoanStatusData["LoanNumber"]);
            $loanDetails->setLoanStatus($activeLoanStatusData["Status"][1]);
        }
        
        $loanAmountDueResponse = $soapClient->GetLoanAmountDue([
                'loanNumber' => $loanDetails->getLoanNumber()
            ]);
        $loanAmountDueData = $this->__convertXMLToArray($loanAmountDueResponse->GetLoanAmountDueResult);
        if(is_array($loanAmountDueData)){
            $loanDetails->setLoanAmountDue($loanAmountDueData["Status"]);
        }
        
        $daysInArrearsResponse = $soapClient->GetDaysInArrears([
                'loanNumber' => $loanDetails->getLoanNumber()
            ]);
            
        $daysInArrearsData = $this->__convertXMLToArray($daysInArrearsResponse->GetDaysInArrearsResult);
        if(is_array($daysInArrearsData)){
            $loanDetails->setDaysInArrears($daysInArrearsData["Days"]);
            $loanDetails->setAmountInArrears($daysInArrearsData["Amount"]);
        }
        
        $repaymentRateResponse = $soapClient->GetRepaymentRate([
                'loanNumber' => $loanDetails->getLoanNumber()
            ]);
        $repaymentRateData = $this->__convertXMLToArray($repaymentRateResponse->GetRepaymentRateResult);
        if(is_array($repaymentRateData)){
            $loanDetails->setRepaymentRate($repaymentRateData["Rate"]);
        }
        
        $averageLoanAmountResponse = $soapClient->AverageLoanAmount([
                'clientCode' => $loanDetails->getClientCode()
            ]);
        $averageLoanAmountData = $this->__convertXMLToArray($averageLoanAmountResponse->AverageLoanAmountResult);
        if(is_array($averageLoanAmountData)){
            $loanDetails->setAverageLoanAmount($averageLoanAmountData["Average"]);
        }
        
        $numberOfLoansDisbursedResponse = $soapClient->CountLoansDisbursed([
                'clientCode' => $loanDetails->getClientCode()
            ]);
        $numberOfLoansDisbursedData = $this->__convertXMLToArray($numberOfLoansDisbursedResponse->CountLoansDisbursedResult);
        if(is_array($numberOfLoansDisbursedData)){
            $loanDetails->setNumberOfLoansDisbursed($numberOfLoansDisbursedData["Count"]);
        }
        var_dump($loanDetails);
        
//        $this->set("xmlData", $values); 
    }

    public function index()
    {
        
    }
}
