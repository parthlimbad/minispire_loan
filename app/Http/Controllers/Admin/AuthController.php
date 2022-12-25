<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\API\LoginAPIRequest;
use App\Repositories\AdminRepository;
use App\Traits\CommonTrait;

class AuthController extends BaseController
{
    use CommonTrait;

    /**
     * Constructor
     */
    private $_adminRepo;

    public function __construct(
        AdminRepository $adminRepo
    ) {
        $this->_adminRepo = $adminRepo;
    }

    /**
     * Admin Login 
     *
     * @param LoginAPIRequest $request
     * @return string
     * @author Parth L.
     */
    public function login(LoginAPIRequest $request)
    {
        $input = $request->validated();
            
        $user = $this->_adminRepo->findWhere(['email' => $input['email']])->first();
        if(!$user){
            return $this->error("Admin user not found!", 404);
        } else {

            if (! Hash::check($input['password'], $user->password)){
                return response()->json([
                    'message' => 'Email or password is incorrect!'
                ], 403);
            }

            // Generate access token 
            $tokenResult = $user->createToken('Minispire Admin Token');
            $token = $tokenResult->token;
            $token->save();

            return $this->success("Admin Login successfully.", [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $tokenResult->token->expires_at,
            ]);
        }
    }

    /**
     *  Logout Admin
     *
     * @return string
     * @author Parth L.
     */
    public function logout()
    {
        try {
            $loggedInAdmin = Auth::guard('admin')->user();
                if($loggedInAdmin){
                    $loggedInAdmin->token()->revoke();;
                    return $this->success('Admin Logged out successfully.');
                }
                return $this->success('No Logged In User Found');
        } catch (\Throwable $th) {
             throw $th;
            return $this->error('Error when logging out!', 500);
        }
    }
}
