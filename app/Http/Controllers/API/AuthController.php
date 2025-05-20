<?php

namespace App\Http\Controllers\API;
  
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
class AuthController extends BaseController
{
 
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */


     
    public function register(Request $request) {
       
         
try{

        $validator= Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'c_password'=>'required|same:password'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.',$validator->errors());
        }
        error_log("zaburi");
        $input = $request->all();
        $input['password']=bcrypt($input['password']);
         $user=User::create($input);
        $success['user']=$user;
        return $this->sendResponse($success,'User register successfully');
}catch(Exception $e){
    error_log('message here.uyvyyv7igufviytcuutjcuytctycvu');
}
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
  
        if (! $token = auth()->attempt($credentials)) {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
       $success = $this->respondWithToken($token);
       return $this->sendResponse($success, 'User login successfully.');
}
  
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $success = auth()->user();
   
        return $this->sendResponse($success, 'User Profile return successfully.');
    }
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        
        return $this->sendResponse([], 'Successfully logged out.');
    }
  
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $success = $this->respondWithToken(auth()->refresh());
   
        return $this->sendResponse($success, 'Refresh token return successfully.');
    }
  
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
           // 'expires_in' => auth()->factory()->getTTL() * 60,
            'expires_in' => auth('api')->factory()->getTTL() * 60 * 60 * 14000000000,

        ];
    }

    protected function respondWithToken($token)
{
    return [
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth('api')->factory()->getTTL() * 60, 
    ];
}
}
