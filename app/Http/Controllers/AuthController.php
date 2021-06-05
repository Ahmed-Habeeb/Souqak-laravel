<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','create']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function create(Request $request){
        $validator =Validator::make($request->all(),
            ['name'=>'required',
            'email'=>'required|unique:users|email:rfc,dns',
            'password'=>'required|min:8',
            ]);

        if ($validator -> fails()) {
            return response()->json($validator->errors(),201);

        }

        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        return $this->login($request);


    }

    public function login(Request $data)
    {
        $validator =Validator::make($data->all(),
            [
                'email'=>'required|email:rfc,dns',
                'password'=>'required|min:8',
            ]);

        if ($validator -> fails()) {
            return response()->json($validator->errors());

        }
        $credentials = $data->only(['email', 'password']);
        $token = auth('api')->attempt($credentials);


        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user=auth('api')->user();
        $user->token=$token;

        return response()->json($user);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $header = $request->header('Authorization');

        $user=auth('api')->user();
        $user->token=$header;
        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
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
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
