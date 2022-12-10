<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Models\Loan;
use App\Models\Repayment;
use App\Repositories\Repository;

class RepaymentRepository extends Repository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'loan_id',
        'amount',
        'pay_date',
        'paid',
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
     */
    public function model()
    {
        return Repayment::class;
    }
}
