<?php

namespace Dcms\Core\Http\Controllers\Settings;

use Dcms\Core\Models\Countries\Country;

use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatables;
use Auth;
use DateTime;
use Dcms\Core\Http\Controllers\BaseController;

class CountryController extends BaseController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// load the view
			return View::make('dcms::settings/countries/index');
	}

	public function getDatatable()
	{
        return Datatables::queryBuilder(DB::connection("project")
                                                ->table("countries")
												->select(
															'countries.id',
															'countries.country_name',
															(DB::connection("project")->raw('Concat("<img src=\'/packages/dcms/core/images/flag-",lcase(country),".png\' >") as country'))
														)->orderBy('countries.country_name'))
                        ->addColumn('edit', '<form class="pull-right"> <a class="btn btn-xs btn-default" href="/admin/settings/countries/{{$id}}/edit"><i class="fa fa-pencil"></i></a></form>')
                        ->rawColumns(['country','edit'])
                        ->make(true) ;
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		// load the create form (app/views/articles/create.blade.php)
		return View::make('dcms::settings/countries/form');
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
			'country'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the validator
		if ($validator->fails()) {
			return Redirect::to('admin/settings/countries/create')
				->withErrors($validator)
				->withInput();
				//->withInput(Input::except());
		} else {
			// store
			$input = Input::get();

			$Country = new Country;
			$Country->country = $input['country'];
			$Country->country_name = $input['country_name'];
			$Country->save();

			// redirect
			Session::flash('message', 'Successfully created country! Do not forget to upload the flag');
			return Redirect::to('admin/settings/countries');
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
			// get the country
			$Country = Country::find($id);

			// show the edit form and pass the nerd
			return View::make('dcms::settings/countries/form')
				->with('Country', $Country);
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
			'country'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/settings/countries/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} else {
			// store

			$input = Input::get();

			$Country = Country::find($id);
			$Country->country = $input['country'];
			$Country->country_name = $input['country_name'];
			$Country->save();

			// redirect
			Session::flash('message', 'Successfully updated country!');
			return Redirect::to('admin/settings/countries');
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
		//$Country = Country::find($id);
		//$Country->delete();

		// redirect
		Session::flash('message', 'Sorry nothing has been deleted, ask your DBA for thorough delete!');
		return Redirect::to('admin/settings/countries');
	}

}
?>
