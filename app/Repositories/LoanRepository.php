<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Loan;
use App\Models\Repayment;
use App\Repositories\Repository;

class LoanRepository extends Repository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'amount',
        'currency',
        'duration',
        'first_paydate'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     * @author Parth L.
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     * 
     * @return Model
     * @author Parth L.
     **/
    public function model()
    {
        return Loan::class;
    }

    /**
     * Create Loan Model
     *
     * @param array $data
     * @return Model
     * @author Parth L.
     */
    public function create($data)
    {
        try {
            DB::beginTransaction();
            $loan = $this->model->create($data);
            $repaymentList =  $loan->repayments()->saveMany($this->generateRepayments($loan));
            $loan->refresh();
            DB::commit();
            $loan = $loan->toArray();
            $repaymentList = json_decode(json_encode($repaymentList), true);
            return ['loan'=> $loan, 'repayments'=>$repaymentList];
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }

    /**
     * Generate Repayments for a while Loan is created
     *
     * @param Loan $loan
     * @return array
     * @author Parth L.
     */
    private function generateRepayments($loan)
    {
        // Repayment dates
        $paydateEnd = Carbon::parse($loan->first_paydate)->addWeeks($loan->duration);
        $period = Carbon::parse($loan->first_paydate)->toPeriod($paydateEnd, '1 week');

        // calculate weekly payment amount
        $totalPayTimes = $loan->duration;
        $payAmountMonthly = floor($loan->amount / $totalPayTimes);
        $payAmountRemain = $loan->amount - ($payAmountMonthly * $totalPayTimes);

        $repaymentList = [];

        foreach($period as $index => $date) {
            if ($index < $loan->duration) {
                $_amount = $payAmountMonthly;
            }else if($index == $loan->duration) {
                $_amount = $payAmountRemain;
            }
            if($_amount <= 0) {
                continue;
            }
            $repaymentList [] = new Repayment([
                'loan_id' => $loan->id,
                'amount' => $_amount,
                'pay_date' => $date->format('Y-m-d'),
                'paid' => false,
                'currency' => $loan->currency,
            ]);
        }

        return $repaymentList;
    }
}
