<?php

namespace App\Controller;

require_once(ROOT . DS . 'vendor' . DS . "Classes" . DS . "PHPExcel.php");

use App\Controller\AppController;
use App\Model\CustomerLoanDetails;
use App\Model\VslaCreditScore;
use App\Helpers\DatabaseHandler;
use App\Repository\VslaRepo;
use App\Repository\VslaCycleRepo;
use App\Repository\AttendanceRepo;
use App\Repository\SavingRepo;
use App\Repository\LoanIssueRepo;
use PHPExcel;




/**
 * LoanPerformer Controller
 *
 * @property \App\Model\Table\LoanPerformerTable $LoanPerformer
 */
class LoanPerformerController extends AppController
{
    protected $url = "http://104.211.10.181:8080/webservice/foxservice?wsdl";

    public function GetFunctions(){
        $soapClient = new \SoapClient($this->url);
        var_dump($soapClient->__getFunctions());
    }
    
    protected function __convertXMLToArray($xmlData){
        
        return json_decode(json_encode(simplexml_load_string($xmlData)), 1);
    }
    
    protected function __getLoanRepaymentHistory($daysInArrears){
        return $loanRepaymentHistory = $daysInArrears > 1 && $daysInArrears < 90 ? "Delinquent Situation" : ($daysInArrears > 90 ? "Defaulted" : "No Delinquent Situation");
    }

    public function getLoanPerformerVariables(){

        $vslaID = $this->request->query["vsla_id"];
        $requestedLoanAmount = $this->request->query["requested_loan_amount"];
        $vslaCreditScore = new VslaCreditScore();
        $db = DatabaseHandler::getInstance();
        
        if($vslaID == null & $requestedLoanAmount == null){
            $this->set("jsonData", json_encode(array("Status" => "FAILURE", "Message" => "No values provided for either vsla_id and requested_loan_amount variables")));
            return;
        }
        
        $vsla = (new VslaRepo($vslaID))->getVsla();
        if($vsla->getGroupAccountNumber() == null){
            $this->set("jsonData", json_encode(array("Status" => "FAILURE", "Message" => "Please add the vsla group account number")));
            return;
        }
        
        $currentCycleID = VslaCycleRepo::getCurrentCycleID($db, $vsla->getID());
        if($currentCycleID == false){
            $this->set("jsonData", json_encode(array("Status" => "FAILURE", "Message" => "The " . $vsla->getVslaName() . " has no cycle information")));
            return;
        }
        
        $vslaCreditScore->setClientCode($vsla->getGroupAccountNumber());
        $vslaCreditScore->setRequestedLoanAmount($requestedLoanAmount);
        $isConnectionEstablished = false;
        
        try{
            $soapClient = new \SoapClient($this->url);
            $isConnectionEstablished = true;
        }catch(Exception $e){
            $this->set("jsonData", json_encode(array("Status" => "FAILURE", "Message" => "Failed to establish a connection to the Loan Performer server")));
            return;
        }
        if($isConnectionEstablished){
            $response = $soapClient->ActiveLoanStatus([
                'clientCode' => $vslaCreditScore->getClientCode()
            ]);
            
            $activeLoanStatusData = $this->__convertXMLToArray($response->ActiveLoanStatusResult);
            if(is_array($activeLoanStatusData)){
                $vslaCreditScore->setPreviousLoanNumber($activeLoanStatusData["LoanNumber"]);
                $vslaCreditScore->setLoanStatus($activeLoanStatusData["Status"][1]);
            }
            
            $checkLoanBalanceResponse = $soapClient->CheckLoanBalance([
                "username" => "Link_ko",
                "lnr" => $vslaCreditScore->getPreviousLoanNumber()
            ]);
            $checkLoanBalanceData = $this->__convertXMLToArray($checkLoanBalanceResponse->CheckLoanBalanceResult);
            if(is_array($checkLoanBalanceData)){
                $vslaCreditScore->setPreviousLoanAmount($checkLoanBalanceData["Data"]["OutstandingBalance"]["Principal"]);
            }

            $loanAmountDueResponse = $soapClient->GetLoanAmountDue([
                    'loanNumber' => $vslaCreditScore->getPreviousLoanNumber()
                ]);
            $loanAmountDueData = $this->__convertXMLToArray($loanAmountDueResponse->GetLoanAmountDueResult);
            if(is_array($loanAmountDueData)){
                $vslaCreditScore->setLoanAmountDue($loanAmountDueData["Status"]);
            }

            $daysInArrearsResponse = $soapClient->GetDaysInArrears([
                    'loanNumber' => $vslaCreditScore->getPreviousLoanNumber()
                ]);

            $daysInArrearsData = $this->__convertXMLToArray($daysInArrearsResponse->GetDaysInArrearsResult);
            if(is_array($daysInArrearsData)){
                $vslaCreditScore->setDaysInArrears($daysInArrearsData["Days"]);
                $vslaCreditScore->setAmountInArrears($daysInArrearsData["Amount"]);
            }
            
            $vslaCreditScore->setLoanRepaymentHistory($this->__getLoanRepaymentHistory($vslaCreditScore->getDaysInArrears()));

            $repaymentRateResponse = $soapClient->GetRepaymentRate([
                    'loanNumber' => $vslaCreditScore->getPreviousLoanNumber()
                ]);
            $repaymentRateData = $this->__convertXMLToArray($repaymentRateResponse->GetRepaymentRateResult);
            if(is_array($repaymentRateData)){
                $vslaCreditScore->setLoanRepaymentRate($repaymentRateData["Rate"]/100);
            }

            $averageLoanAmountResponse = $soapClient->AverageLoanAmount([
                    'clientCode' => $vslaCreditScore->getClientCode()
                ]);
            $averageLoanAmountData = $this->__convertXMLToArray($averageLoanAmountResponse->AverageLoanAmountResult);
            if(is_array($averageLoanAmountData)){
                $vslaCreditScore->setAverageLoanAmount($averageLoanAmountData["Average"]);
            }

            $numberOfLoansDisbursedResponse = $soapClient->CountLoansDisbursed([
                    'clientCode' => $vslaCreditScore->getClientCode()
                ]);
            $numberOfLoansDisbursedData = $this->__convertXMLToArray($numberOfLoansDisbursedResponse->CountLoansDisbursedResult);
            if(is_array($numberOfLoansDisbursedData)){
                $vslaCreditScore->setNumberOfLoansDisbursed($numberOfLoansDisbursedData["Count"]);
            }
            
            $creditScoreTool = "CreditScoreTool/VSLACreditScoreCardTool_RUFI.xlsx";
            $inputFileType = \PHPExcel_IOFactory::identify($creditScoreTool);
            $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setLoadAllSheets();
            $objPHPExcel = $objReader->load($creditScoreTool);
            $objPHPExcel->setActiveSheetIndex(1);
            $objPHPExcel->getActiveSheet()->setCellValue("K13", $vsla->getVslaName());
            
            $averageAttendanceRate = AttendanceRepo::getAttendanceRate($db, $currentCycleID);
            $volumeOfSavingsInCurrentCycle = SavingRepo::getVolumeOfSavingsInCycle($db, $currentCycleID);
            $volumeOfSavingsInPreviousCycle = 0;
            $loanFundUtilization = LoanIssueRepo::getLoanFundUtilization($db, $currentCycleID);
            $percentageOfMembersWithActiveLoans = LoanIssueRepo::getPercentageOfMembersWithActiveLoan($db, $vslaID, $currentCycleID);
            $prevCycleID = VslaCycleRepo::getPreviousCycleID($db, $vsla->getID());
            if($prevCycleID > 0){
                $volumeOfSavingsInPreviousCycle = SavingRepo::getVolumeOfSavingsInCycle($db, $prevCycleID);
            }

            $vslaCreditScore->setNumberOfYearsOfOperation(2);
            $vslaCreditScore->setAverageAttendanceRate($averageAttendanceRate != false ? $averageAttendanceRate : 0);
            $vslaCreditScore->setVolumeOfSavingsInPreviousCycle($volumeOfSavingsInPreviousCycle != false ? $volumeOfSavingsInPreviousCycle : 0);
            $vslaCreditScore->setLoanFundUtilization($loanFundUtilization != false ? $loanFundUtilization : 0);
            $vslaCreditScore->setVolumeOfSavingsInCurrentCycle($volumeOfSavingsInCurrentCycle != false ? $volumeOfSavingsInCurrentCycle : 0);
            $vslaCreditScore->setPercentageOfMembersWithActiveLoans($percentageOfMembersWithActiveLoans != false ? $percentageOfMembersWithActiveLoans : 0);
            $vslaCreditScore->setLoanWriteOffInPreviousCycle(0.03);
            $vslaCreditScore->setPortfolioAtRisk(0.04);
            $vslaCreditScore->setDateProcessed(date("Y-m-d"));
            
            
            //Loan Request Amount
            $objPHPExcel->getActiveSheet()->setCellValue("K15", doubleval($vslaCreditScore->getRequestedLoanAmount()));

            //Previous Loan Amount
            $objPHPExcel->getActiveSheet()->setCellValue("K21", doubleval($vslaCreditScore->getPreviousLoanAmount()));

            //Amount In Arrears
            $objPHPExcel->getActiveSheet()->setCellValue("K23", doubleval($vslaCreditScore->getAmountInArrears()));

            //Loan Repayment History - Currently not available from Loan Performer
            $objPHPExcel->getActiveSheet()->setCellValue("K25", doubleval($vslaCreditScore->getLoanRepaymentHistory()));

            //Loan Repayment Rate
            $objPHPExcel->getActiveSheet()->setCellValue("K27", doubleval($vslaCreditScore->getLoanRepaymentRate()));

            //Number of previous loans
            $objPHPExcel->getActiveSheet()->setCellValue("K29", doubleval($vslaCreditScore->getNumberOfLoansDisbursed()));

            //Number of years of operation - Provided from LedgerLink
            $objPHPExcel->getActiveSheet()->setCellValue("K33", doubleval($vslaCreditScore->getNumberOfYearsOfOperation()));

            //Average Attendance Rate - Provided from LedgerLink
            $objPHPExcel->getActiveSheet()->setCellValue("K36", doubleval($vslaCreditScore->getAverageAttendanceRate()));

            //Volume of savings from previous cycle - Provided from LedgerLink
            $objPHPExcel->getActiveSheet()->setCellValue("K38", doubleval($vslaCreditScore->getVolumeOfSavingsInPreviousCycle()));

            //Loan Fund Utilization - Provided from LedgerLink
            $objPHPExcel->getActiveSheet()->setCellValue("K40", doubleval($vslaCreditScore->getLoanFundUtilization()));

            //Volume of Savings in Current Cycle - Provided from LedgerLink
            $objPHPExcel->getActiveSheet()->setCellValue("K42", doubleval($vslaCreditScore->getVolumeOfSavingsInCurrentCycle()));
            
            //Percentage of members with an active loan
            $objPHPExcel->getActiveSheet()->setCellValue("K44", doubleval($vslaCreditScore->getPercentageOfMembersWithActiveLoans()));

            //Loan written off in previous cycle - Provided from LedgerLink
            $objPHPExcel->getActiveSheet()->setCellValue("K46", doubleval($vslaCreditScore->getLoanWriteOffInPreviousCycle()));
            
            //Portfolio at risk - Provided from LedgerLink
            $objPHPExcel->getActiveSheet()->setCellValue("K48", doubleval($vslaCreditScore->getPortfolioAtRisk()));

            $fileName = "CreditScoreTool/VSLACreditScoreCardTool-RUFI" . substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 5) . "" . strtotime(date("Y-m-d")) . ".xlsx";
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->setPreCalculateFormulas(true);
            $objWriter->save($fileName);

            $objPHPScore = \PHPExcel_IOFactory::load($fileName);
            $objPHPScore->setActiveSheetIndex(2);

            $vslaCreditScore->setFinalScore($objPHPScore->getActiveSheet()->getCell("J12")->getCalculatedValue() * 100);
            $vslaCreditScore->setDecision($objPHPScore->getActiveSheet()->getCell("J16")->getCalculatedValue());
            $vslaCreditScore->setAssessmentAmount(round($objPHPScore->getActiveSheet(0)->getCell("J23")->getCalculatedValue(),0));


            $this->set("jsonData", json_encode(array(
                "RequestedLoanAmount" => $vslaCreditScore->getRequestedLoanAmount(),
                "DaysInArrears" => $vslaCreditScore->getDaysInArrears(),
                "AmountInArrears" => $vslaCreditScore->getAmountInArrears(),
                "LoanRepaymentHistory" => $vslaCreditScore->getLoanRepaymentHistory(),
                "LoanRepaymentRate" => $vslaCreditScore->getLoanRepaymentRate(),
                "NumberOfLoansDisbursed" => $vslaCreditScore->getNumberOfLoansDisbursed(),
                "NumberOfYearsOfOperation" => $vslaCreditScore->getNumberOfYearsOfOperation(),
                "AverageAttendanceRate" => $vslaCreditScore->getAverageAttendanceRate(),
                "VolumeOfSavingsInPreviousCycle" => $vslaCreditScore->getVolumeOfSavingsInPreviousCycle(),
                "LoanFundUtilization" => $vslaCreditScore->getLoanFundUtilization(),
                "VolumeOfSavingsInCurrentCycle" => $vslaCreditScore->getVolumeOfSavingsInCurrentCycle(),
                "PercentageOfMembersWithActiveLoan" => $vslaCreditScore->getPercentageOfMembersWithActiveLoans(),
                "LoanWriteOffInPreviousCycle" => $vslaCreditScore->getLoanWriteOffInPreviousCycle(),
                "PortfolioAtRisk" => $vslaCreditScore->getPortfolioAtRisk(),
                "FinalScore" => $vslaCreditScore->getFinalScore(),
                "Decision" => $vslaCreditScore->getDecision(),
                "AssessmentAmount" => $vslaCreditScore->getAssessmentAmount(),
                "ClientCode" => $vslaCreditScore->getClientCode(),
                "DateProcessed" => $vslaCreditScore->getDateProcessed(),
                "PreviousLoanNumber" => $vslaCreditScore->getPreviousLoanNumber(),
                "PreviousLoanAmount" => $vslaCreditScore->getPreviousLoanAmount(),
                "Status" => "SUCCESSFUL"
                )));
             
        }else{
            $this->set("jsonData", json_encode(array("Status" => "FAILURE", "Message" => "Could not establish connection to Loan Performer Server")));
        }
    }

    public function index()
    {
        
    }
}
