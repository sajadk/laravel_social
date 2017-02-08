<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\User;

Class UserController extends Controller
{

	public function postSignUp(Request $request)
	{
		$this->validate($request,[
			'email' => 'required|email|unique:users',
			'first_name' => 'required|max:120',
			'password' => 'required|min:6'

			]);
		$email = $request['email'];
		$first_name = $request['first_name'];
		$password = bcrypt($request['password']);

		$user = new User();
		$user->email = $email;
		$user->first_name = $first_name;
		$user->password = $password;

		$user->save();

		Auth::login($user);

		return Redirect()->route('dashboard');
	}

	public function postSignIn(Request $request)
	{
		$this->validate($request,[
			'email' => 'required|email',
			'password' => 'required'

			]);
		if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']]))
		{
			return Redirect()->route('dashboard');
		}
		return Redirect()->back();
	}
	public function getLogout()
	{
		Auth::logout();
		return Redirect()->route('home');
	}

	public function getAccount()
	{
		$user = Auth::user();
		return view('account',['user' => $user ]);
	}
	public function postSaveAccount(Request $request)
	{
		$this->validate($request,[
			'first_name' => 'required|max:120'
			]);

		$user_id = Auth::user()->id;
        $user = User::find($user_id);
		$user->first_name = $request['first_name'];
		$user->update();
		$file = $request->file('image');
		
	
		$filename = $request['first_name'] . '-' . $user->id .'.jpg';
		if($file)
		{

			Storage::disk('local')->put($filename,File::get($file));
		}
		return Redirect()->route('account');
	}
	public function getUserImage($filename){
         $file = Storage::disk('local')->get($filename);
         return new Response($file,200);	
	}
}
