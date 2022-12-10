<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;


use App\Models\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Loan;
use App\Repositories\LoanRepository;

class LoanTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install',['-vvv' => true,'--no-interaction' => true]);
    }

    public function test_user_can_get_loans_list()
    {

        $route = route('loans.detail');

        list($user, $accessToken) = $this->getUser();

        $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$accessToken 
            ])->getJson($route);

        $response->assertStatus(200);
    }

    protected function getUser()
    {
        $user = User::updateOrCreate(
            ['email' => 'user1@example.com', ],
            [
                'name' => 'User 1', 
                'email' => 'user1@example.com', 
                'email_verified_at' => null, 
                'password' => Hash::make('password'), 
                'remember_token' => null,
            ]
        );

        $tokenResult = $user->createToken('TestToken');

        return [$user, $tokenResult->accessToken];
    }
}
