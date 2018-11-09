<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
	
	/**
     * Display the password reset view for the given token.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {	
		// find which hashed token in the database can result from the given plaintext token
		$app_hasher = app()['hash'];
		$entries = DB::table('password_resets')->get();
		
		$hashed_token = "";
		foreach ($entries as $entry) {
			$hash_check = $app_hasher->check($token, $entry->token);
			
			if ($hash_check !== null && $hash_check == 1) {
				$request->email = $entry->email;
				break;
			}
		}
		
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
	
	public function sendResetResponse($response)
    {
		return redirect()->action('ProjectController@index')->with('k3_global_success', 'password_reset');
    }
	
    public function preValidateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'email' => 'required|email',
        ]);
        
        if ($validator->fails()) {
			return response()->json(['response' => 'Invalid email'], 422);
        }
		
		$user = User::where('email', '=', $request->email)->first();
		
		if ($user === null) {
			return response()->json(['response' => 'There is no user associated with that email'], 422);
		} elseif ($user->active) {
			return response()->json(['response' => 'Found'], 200);
		} else {
			return response()->json(['response' => 'Please activate your account before resetting your password'], 403);
		}
    }
	
}
