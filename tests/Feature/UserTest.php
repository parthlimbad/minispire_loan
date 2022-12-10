<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;


class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install',['-vvv' => true,'--no-interaction' => true]);
    }

    public function test_user_login()
    {
        $route = route('auth.login');

        $user = Passport::actingAs(
        User::factory()->create());

        // $user = User::factory()->create();
        // $token = $user->createToken('TestToken')->accessToken;
        
        $body = [
            'email' => $user->email,
            'password' => 'password', // default password is set in UserFactory
        ];

        $response = $this->postJson($route, $body);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }
    
    public function test_create_loan_for_user()
    {
        $route = route('loan.create');

        $user = Passport::actingAs(
        User::factory()->create());

        $testClientAccessToken =  $user->createToken('TestToken')->accessToken;

        $body = [
            "user_id" => $user->id,
            "amount" => 150,
            "currency" => "USD",
            "duration" => 3,
            "first_paydate" => Carbon::now()
        ];

        // Admin can create loan for user only
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$testClientAccessToken 
        ])->postJson($route, $body);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
        ]);
        return $response;
    }

    public function test_user_can_logout()
    {
        $route = route('auth.logout');
        
        list($testUser, $testClientAccessToken) = $this->getTestClientUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$testClientAccessToken 
        ])->postJson($route);
        
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_user_can_not_login_admin_route()
    {
        $route = route('admin.auth.login');

        $user = Passport::actingAs(
        User::factory()->create());

        $body = [
            'email' => $user->email,
            'password' => 'password', // default password is set in UserFactory
        ];

        $response = $this->postJson($route, $body);
        $response->assertStatus(404);
    }

    public function test_user_can_make_repayment()
    {
        $routeUserLoans = route('loan.repay');

        list($user, $accessToken) = $this->getTestClientUser();

        // Be sure user has some loans
        $testLoan =  $this->test_create_loan_for_user($user->id);
        $loanResponseDecoded = json_decode($testLoan->getContent(), true);

        $loanId = $loanResponseDecoded['data']['loan']['id'];
        $payDate = $loanResponseDecoded['data']['repayments']['0']['pay_date'];
        $payDate = date("Y-m-d", strtotime($payDate));
        $paidAmount = 159;
        // Step 1: Get list of loans of users
        $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$accessToken 
            ])->putJson($routeUserLoans, ['loan_id' => $loanId,'pay_date' => $payDate, 'paid_amount' => $paidAmount] );

        // $responseData = json_decode($response->getContent(), true);;
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
        ]);


        $loanId = $loanResponseDecoded['data']['loan']['id'];
        $payDate = $loanResponseDecoded['data']['repayments']['1']['pay_date'];
        $payDate = date("Y-m-d", strtotime($payDate));
        $paidAmount = 1;

        $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$accessToken 
            ])->putJson($routeUserLoans, ['loan_id' => $loanId,'pay_date' => $payDate, 'paid_amount' => $paidAmount] );

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
        ]);
    }

    public function test_user_can_not_make_less_repayment()
    {
        $routeUserLoans = route('loan.repay');

        list($user, $accessToken) = $this->getTestClientUser();

        $testLoan =  $this->test_create_loan_for_user($user->id);
        $loanResponseDecoded = json_decode($testLoan->getContent(), true);

        $loanId = $loanResponseDecoded['data']['loan']['id'];
        $payDate = $loanResponseDecoded['data']['repayments']['0']['pay_date'];
        $payDate = date("Y-m-d", strtotime($payDate));
        $paidAmount = 1;
        
        $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$accessToken 
            ])->putJson($routeUserLoans, ['loan_id' => $loanId,'pay_date' => $payDate, 'paid_amount' => $paidAmount] );

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
        ]);
    }

    protected function getTestClientUser()
    {
        $user = User::updateOrCreate(
            ['email' => 'user2@example.com', ],
            [
                'name' => 'User 2', 
                'email' => 'user2@example.com', 
                'email_verified_at' => null, 
                'password' => Hash::make('password2'), 
                'remember_token' => null,
            ]
        );

        $accessToken = $user->createToken('TestToken')->accessToken;

        return [$user, $accessToken];
    }
}
