<?php

namespace Dcms\Core\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Input;
use View;
use Validator;
use Auth;
use Redirect;
Use Dcms\Core\Models\Users\User;
Use Datatables;
use DB;
use Hash;
use Session;

class UserController extends Controller
{
    protected function isPostRequest()
    {
      return Input::server("REQUEST_METHOD") == "POST";
    }

    protected function getLoginValidator()
    {
      return Validator::make(Input::all(), array(
        "username" => "required",
        "password" => "required"
      ));
    }

    protected function getLoginCredentials()
    {
      return array(
        "username" => Input::get("username"),
        "password" => Input::get("password")
      );
    }

    public function login()
    {
      if ($this->isPostRequest())
      {
        $validator = $this->getLoginValidator();

        if ($validator->passes()) {
          $credentials = $this->getLoginCredentials();

          if (Auth::guard('dcms')->attempt($credentials)) {
  					if(session_id() == '') session_start();
  					$_SESSION["admin"]["allow_ckfinder"] = true;

  					$User = User::find(Auth::guard('dcms')->user()->id);
  					$User->last_login = date("Y-m-d H:i:s");
  					$User->save();

  					  return Redirect::intended("admin/dashboard"); //intended will keep in mind your entry point, if none has been found a default is given
          //  return Redirect::route("admin/dashboard");
          }

          return Redirect::back()->withErrors(array( "password" => array("Username and/or password invalid.")));

        } else {
          return Redirect::back()
            ->withInput()
            ->withErrors($validator);
        }
      }
      return View::make("dcms::users/login");
    }

  	public function profile()
  	{
  		$user = User::find(Auth::guard('dcms')->user()->id);
  		$user->password = "";

  		return View::make("dcms::users/profile")->with('user',$user);
  	}

  	public function updateProfile()
  	{
  		$passwordError = false;
  		if ($this->isPostRequest()) {

  			$validatorrules = array("email"=>"required|email");

  			if(trim(Input::get("password"))<>"" || trim(Input::get("newpassword"))<>"" || trim(Input::get("newpasswordrepeat"))<>"" )
  			{
  				$validateCurrentpassword = true;
  				$validatorrules = array_merge($validatorrules, array("password" => "required","newpassword" => "required|min:6","newpasswordrepeat" => "same:newpassword"));
  				if(Hash::check(Input::get('password'),Auth::guard('dcms')->user()->getAuthPassword()) !==  true) $passwordError = true;
  			}

  		 	$validator =  Validator::make(Input::all()
  																		, $validatorrules
  																		,array(	"email"=>"Email should be a valid email"
  																						,"required"=>"The :attribute field is required"
  																						,"password.required"=>"The current password is required"
  																						,"newpassword.required"=>"A new password is required"
  																						,"newpassword.min"=>"A new password needs minimum 6 characters"
  																						,"newpasswordrepeat.same"=>"the new password - repeat field needs to be the same as the new password" ));

        if ($validator->passes() && $passwordError == false) {

  				$user = User::find(Auth::guard('dcms')->user()->id);

  				$user->name		= Input::get('name');
  				$user->email	= Input::get('email');

  				if (strlen(trim(Input::get('newpassword')))>0){
  					$user->password = Hash::make(Input::get('newpassword'));
  				}
  				$user->save();


  				Session::flash('message', 'Successfully updated profile!');
  				return Redirect::to('admin/profile');

  	    } elseif($passwordError == true){
  				  return Redirect::back()
            ->withInput()
            ->withErrors(array("error"=>"Current password does not match"));

  			}else {
          return Redirect::back()
            ->withInput()
            ->withErrors($validator);
        }

      }else{
  			return Redirect::to('admin/profile');
  		}
  	}

  	public function logout()
  	{
  	  Auth::guard('dcms')->logout();

  	  return Redirect::route("admin.login");
  	}







	/*****************************************************
	  CRUD METHODS
	*******************************************************/



	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		// get all the users
//		$users = User::all();
		$users = User::paginate(5);

		// load the view and pass the users
		return View::make('dcms::users/index')
			->with('users', $users);
	}

	public function getDatatable()
	{
        return Datatables::queryBuilder(DB::connection("project")
                                                ->table("users"))
                        ->addColumn('edit', '<form method="POST" action="/admin/users/{{$id}}" accept-charset="UTF-8" class="pull-right"><input name="_token" type="hidden" value="'.csrf_token().'">					<input name="_method" type="hidden" value="DELETE">					<!-- <input class="btn btn-warning" type="submit" value="Delete this User"> -->
								<a class="btn btn-xs btn-default" href="/admin/users/{{$id}}/edit"><i class="fa fa-pencil"></i></a>
								<button class="btn btn-xs btn-default" type="submit" value="Delete this article" onclick="if(!confirm(\'Are you sure to delete this item?\')){return false;};"><i class="fa fa-trash-o"></i></button>
							</form>')
                        ->rawColumns(['edit'])
                        ->make(true) ;
	}



	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		// load the create form (app/views/users/create.blade.php)
		return View::make('dcms::users/form');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
		$rules = array(
			'name'       => 'required',
			'username'       => 'required',
			'email'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);


		// process the validator
		if ($validator->fails()) {
			return Redirect::to('admin/users/create')
				->withErrors($validator)
				->withInput();
				//->withInput(Input::except());
		} else {
			// store
			$user = new User;
			$user->name   		= Input::get('name');
			$user->username   	= Input::get('username');
			$user->email 		= Input::get('email');
			$user->role	 		= Input::get('role');
			$user->password 	= Hash::make(Input::get('password'));
			$this->sendemail();
			$user->save();

			// redirect
			Session::flash('message', 'Successfully created user!');
			return Redirect::to('admin/users');
		}
	}



	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
		// get the user
		$user = User::find($id);
		$user->password = "";

		// show the edit form and pass the nerd
		return View::make('dcms::users/form')
			->with('user', $user);
	}

	/**
	 * Sendemail will send the user his username and password
	 *
	 * @param obj $model
	 */
	protected function sendemail()
	{

		if (Input::get("sendemail")=="1"){
			/*****************************************
				- MAIL SETTINGS -
			*****************************************/
			// I'm creating an array with user's info but most likely you can use $user->email or pass $user object to closure later
			$emailuser = array(
				'email'=>Input::get('email'),
				'name'=>Input::get('name'),
				'username'=>Input::get('username'),
				'password'=>Input::get('password')
			);

			// the data that will be passed into the mail view blade template
			$data = array(
				'name'  => 'name: <b>'.$emailuser['name'].'</b>',
				'username'  => 'username: <b>'.$emailuser['username'].'</b>',
				'email'  => 'email: <b>'.$emailuser['email'].'</b>',
				'password'  => 'password: <b>'.$emailuser['password'].'</b>'
			);

			// use Mail::send function to send email passing the data and using the $user variable in the closure
			Mail::send('dcms::users/email', $data, function($message) use ($emailuser)
			{
				$message->from('web@groupdc.be', 'New DCMS User');
				$message->to($emailuser['email'], $emailuser['name'])->subject('Welcome to My Laravel app!');
			});
			/*****************************************
				- END MAIL  -
			*****************************************/
		}
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		// validate
		// read more on validation at http://laravel.com/docs/validation
		$rules = array(
			'name'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/users/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} else {
			// store
			$user = User::find($id);
			$user->name		= Input::get('name');
			$user->username	= Input::get('username');
			$user->email	= Input::get('email');
			$user->role	= Input::get('role');

			if (strlen(trim(Input::get('password')))>0){
				$user->password = Hash::make(Input::get('password'));
			}
			$this->sendemail();
			$user->save();

			// redirect
			Session::flash('message', 'Successfully updated user!');
			return Redirect::to('admin/users');
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		// delete
		$user = User::find($id);
		$user->delete();

		// redirect
		Session::flash('message', 'Successfully deleted the user!');
		return Redirect::to('admin/users');
	}

	/*****************************************************
	 END CRUD METHODS
	*******************************************************/


}

 ?>
