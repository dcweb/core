<?php

namespace Dcms\Core\Http\Controllers\Settings;

use Dcms\Core\Models\Countries\Country;
use Dcms\Core\Models\Languages\Language;

use View;
use Input;
use Session;
use Validator;
use Redirect;
use DB;
use Datatable;
use Auth;
use DateTime;
use Request;
use Route;

use Dcms\Core\Http\Controllers\BaseController;

class LanguageController extends BaseController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// load the view
		return View::make('dcms::settings/languages/index');
	}

	public function getDatatable()
	{
		return Datatable::Query(
									DB::connection('project')
											->table('languages')
											->select(
														'languages.id',
														'languages.language',
														'languages.language_name',
														(DB::connection("project")->raw('Concat("<img src=\'/packages/dcweb/dcms/assets/images/flag-",lcase(country),".png\' >") as country'))
													)
											//->join('articles_language','articles.id','=','articles_language.article_id')
											//->leftJoin('languages','articles_language.language_id', '=' , 'languages.id')
		)

						->showColumns('language_name','country')
						->addColumn('edit',function($model){return '<form class="pull-right"> <a class="btn btn-xs btn-default" href="/admin/settings/languages/'.$model->id.'/edit"><i class="fa fa-pencil"></i></a></form>';})
						->searchColumns('language_name')
						->make();
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('dcms::settings/languages/form')
					->with('countryOptionValues',Country::OptionValueArray(false));
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
			'language'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the validator
		if ($validator->fails()) {
			return Redirect::to('admin/settings/languages/create')
				->withErrors($validator)
				->withInput();
				//->withInput(Input::except());
		} else {
			// store
			$input = Input::get();

			$Language = new Language;
			$Language->country_id = $input['country_id'];
			$Language->language = $input['language'];
			$Language->language_name = $input['language_name'];
			$Language->country = strtoupper($this->getCountry($input['country_id']));
			$Language->save();

			// redirect
			Session::flash('message', 'Successfully created language!');
			return Redirect::to('admin/settings/languages');
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
		$Language = Language::find($id);

		// show the edit form and pass the nerd
		return View::make('dcms::settings/languages/form')
					->with('Language',$Language)
					->with('countryOptionValues',Country::OptionValueArray(false));
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
			'language'       => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);

		// process the login
		if ($validator->fails()) {
			return Redirect::to('admin/settings/languages/' . $id . '/edit')
				->withErrors($validator)
				->withInput();
		} else {
			// store
			$input = Input::get();

			$Language = Language::find($id);
			$Language->country_id = $input['country_id'];
			$Language->language = $input['language'];
			$Language->language_name = $input['language_name'];
			$Language->country = strtoupper($this->getCountry($input['country_id']));

			$Language->save();

			// redirect
			Session::flash('message', 'Successfully updated country!');
			return Redirect::to('admin/settings/languages');
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


	private function getCountry($id)
	{
			$Country = Country::find($id);
			return $Country->country;
	}
}
?>
