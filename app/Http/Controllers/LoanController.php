<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\API\Client\LoanCreateAPIRequest;
use App\Http\Requests\API\Client\LoanUpdateAPIRequest;
use App\Http\Requests\API\Client\RepaymentUpdateAPIRequest;

use App\Repositories\LoanRepository;
use App\Repositories\RepaymentRepository;
use App\Traits\CommonTrait;
use Illuminate\Support\Facades\Auth;

class LoanController extends BaseController
{
    use CommonTrait;

    private $loanRepo;
    private $repaymentRepo;

    /**
     * Constructor
     */
    public function __construct(
        LoanRepository $loanRepo,
        RepaymentRepository $repaymentRepo
    ) {
        $this->loanRepo = $loanRepo;
        $this->repaymentRepo = $repaymentRepo;
    }

    /**
     * Creates Loan
     *
     * @param LoanCreateAPIRequest $request
     * @return string
     * @author Parth L.
     */
    protected function create(LoanCreateAPIRequest $request)
    {
        try {
            $input = $request->validated();
            $input['user_id'] = empty($input['user_id']) ? Auth::user()->id : $input['user_id'];
            $loan = $this->loanRepo->create($input);
            return $this->success("Loan Created successfully.", $loan); 
        } catch (\Exception $e) {
            throw $e;
            return $this->error("Something when wrong!", 500);
        }
    }

    /**
     * Admin can update any Loan's status (approve or pending)   
     *
     * @param LoanUpdateAPIRequest $request
     * @return string
     */
    protected function update(LoanUpdateAPIRequest $request)
    {  
        try {
            $input = $request->validated();  
            $id = $input['id'];
            $loan = $this->loanRepo->update($id, $input);
            return $this->success("Loan Status Updated successfully.", $loan); 
        } catch (\Exception $e) {
            throw $e;
            return $this->error("Something when wrong!", 500);
        }
    }

    /**
     * Get list of loans belonging to logged in client.
     * 
     * @author Parth L.
     * @return string
     */
    protected function detail()
    {
        $loggedInUserId = Auth::user()->id;
        try {
            $loan = $this->loanRepo->findWhere(['user_id' => $loggedInUserId]);
            if($loan->isEmpty()){
                return $this->success("No active loan found for logged in user", $loan); 
            }
            return $this->success("Loan(s) retrieved successfully.", $loan); 
        } catch (\Exception $e) {
            throw $e;
            return $this->error("Something when wrong!", 500);
        }
    }

    /**
     * Client Repays the loan repayment. If all repayments belonging to a particular loan are paid loan, loan status changes to Paid.
     * 
     * @author Parth L.
     * @param RepaymentUpdateAPIRequest $request
     * @return string
     */
    protected function repay(RepaymentUpdateAPIRequest $request)
    {  
        try {
            $input = $request->validated();           
            $loan = $this->repaymentRepo->findWhere(['loan_id' => $input['loan_id'], 'pay_date'=>$input['pay_date'], 'paid'=>false ])->first();

            if(!empty($loan)){
                $repaymentAmount = $loan->amount;
                if($input['paid_amount'] < $repaymentAmount){
                     return $this->error("Paying amount should not be less than Repayment Amount.", 400); 
                }
                $input['paid_date'] = date('Y-m-d H:i:s');
                $input['paid'] = true;
                $updatedRepayment = $this->repaymentRepo->update($loan->id, $input);


                // Check if all repayments paid to update the loan status accordingly. 
                $unpaidRepayment = $this->repaymentRepo->findWhere(['loan_id' => $input['loan_id'],'paid'=>0]);
                if($unpaidRepayment->isEmpty()){
                    $loan = $this->loanRepo->update($input['loan_id'], ['status'=>'paid']);
                    return $this->success("All Repayments are paid. Loan is Fully Paid", $loan); 
                };
                return $this->success("Repayment Updated successfully.", $updatedRepayment); 
            }
        } catch (\Exception $e) {
            throw $e;
            return $this->error("Something when wrong!", 500);
        }
    }

}
