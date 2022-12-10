<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->double('amount', 10, 2);
            $table->date('pay_date');
            $table->string('currency')->default('USD');
            $table->boolean('paid');
            $table->datetime('paid_date')->nullable();
            $table->double('paid_amount', 10, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();  // Only for UnitTesting: comment this after unit testing's done and uncomment below line 
            // $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

        if(Schema::hasColumn('repayments', 'loan_id') && Schema::hasColumn('repayments', 'pay_date')) {
            Schema::table('repayments', function (Blueprint $table) {
                $table->unique(['loan_id', 'pay_date']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repayments');
    }
}
