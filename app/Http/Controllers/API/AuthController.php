<?php

namespace App\Http\Controllers\API;
  
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use App\Models\Role;

class AuthController extends BaseController
{
 
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
   
public function register(Request $request) {
    try {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'fcm_token' => 'sometimes'
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Find the USER role
        $userRole = Role::where('name', 'USER')->first();
        
        if (!$userRole) {
            Log::error('USER role not found in database');
            return $this->sendError('Registration failed. Please contact support.', [], 500);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['is_active'] = true; 
        $input['role_id'] = $userRole->id; 
        
        $user = User::create($input);
        
        $success['user'] = $user;
        return $this->sendResponse($success, 'User registered successfully');
        
    } catch (\Exception $e) {
        Log::error('Registration Error: ' . $e->getMessage());
        return $this->sendError('Registration failed. Please try again.', [], 500);
    }
}
   
//      public function register(Request $request) {         
// try{
//         $validator= Validator::make($request->all(),[
//             'name'=>'required',
//             'email'=>'required|email',
//             'password'=>'required',
//             'c_password'=>'required|same:password',
//             'fcm_token'=>'sometimes'

//         ]);
//         if($validator->fails()){
//             return $this->sendError('Validation Error.',$validator->errors());
//         }
//      //   error_log("zaburi");
//         $input = $request->all();
//         $input['password']=bcrypt($input['password']);
//          $user=User::create($input);
//         $success['user']=$user;
//         return $this->sendResponse($success,'User register successfully');
// }catch(Exception $e){
//     error_log('message here.uyvyyv7igufviytcuutjcuytctycvu');
// }
//     }
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
}
