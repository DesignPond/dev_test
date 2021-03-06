<?php

use Riiingme\Api\Worker\RiiinglinkWorker;
use Riiingme\Api\Worker\LabelWorker;

use Riiingme\Api\Helpers\ApiHelper;
use Riiingme\User\Repo\UserInterface;

class UserController extends \BaseController {

	protected $riiinglink;
	protected $label;
	protected $user;

	public function __construct(RiiinglinkWorker $riiinglink, LabelWorker $label, UserInterface $user)
	{
		$this->riiinglink = $riiinglink;
		$this->label      = $label;
		$this->user       = $user;

		$this->apiHelper  = new ApiHelper;

		$types   = $this->label->getTypes('titre','id');
		$groupes = $this->label->getGroupes('titre','id');
		$groupes_types = $this->label->getGroupesTypes();

		// The authentification is not used for now, we are faking a user id
		$host_id = 1;

		$user  = $this->user->find($host_id);

		list($user_name, $user_photo) = $this->label->getNameAndPhoto($host_id);

		View::share('user_name', $user_name);
		View::share('user_photo', $user_photo);

		View::share('types', $types);
		View::share('groupes', $groupes);
		View::share('groupes_types', $groupes_types);
	}

	/**
	 * Display a listing of the resource.
	 * GET /user
	 *
	 * @return Response
	 */
	public function index()
	{
		// The authentification is not used for now, we are faking a user id
		$host_id = 1;

		$user        = $this->user->find($host_id);
		$user_labels = $this->label->getLabelsForUserInGroups($host_id);
		$riiinglinks = $this->riiinglink->getRiiinglinksForHost($host_id);
		$thumbs      = $this->label->setInfosForRiiinglinksThumbs($riiinglinks);

		return View::make('admin.users.index')->with(array('user' => $user ,'user_labels' => $user_labels, 'riiinglinks' => $riiinglinks, 'thumbs' => $thumbs));

	}

	/**
	 * Display a listing of the resource.
	 * GET /user
	 *
	 * @return Response
	 */
	public function see()
	{
		// The authentification is not used for now, we are faking a user id
		$host_id = 1;

		$riiinglinks = $this->riiinglink->getRiiinglinksForHost($host_id);
		$thumbs      = $this->label->setInfosForRiiinglinksThumbs($riiinglinks);

		return View::make('admin.users.see')->with(array('riiinglinks' => $riiinglinks, 'thumbs' => $thumbs));

	}

	/**
	 * Show the form for creating a new resource.
	 * GET /user/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /user
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /user/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		/*
		 * Current user
		*/
		$host_riiinglink = $this->riiinglink->getRiiinglink($id);
		// We want all labels for current user in groups
		$host_labels     = $this->label->getLabelsForUserInGroups($host_riiinglink->host_id);
		// We want all labels shared from riiinglink
		$host_metas      = $this->riiinglink->listAllMetasFromRiiinglink($host_riiinglink);

		/*
		 * Invited user from riiinglink
		*/
		$invited_riiinglink = $this->riiinglink->getRiiinglinkWithInvited($host_riiinglink->invited_id,$host_riiinglink->host_id);
		// We want all labels for invited user in groups
		$invited_labels     = $this->apiHelper->dispatchRiiinglinkInGroup($invited_riiinglink);

		return View::make('admin.users.show')->with(array('host_riiinglink' => $host_riiinglink, 'host_labels' => $host_labels, 'host_metas' => $host_metas, 'invited_riiinglink' => $invited_riiinglink, 'invited_labels' => $invited_labels ));
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /user/edit
	 * @return Response
	 */
	public function edit()
	{
		// The authentification is not used for now, we are faking a user id
		$host_id = 1;

		$user_labels = $this->label->getLabelsForUserInGroups($host_id);
		$user_labels = $this->apiHelper->typesLabelsInGroups($user_labels);

		return View::make('admin.users.edit')->with(array('user_labels' => $user_labels));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /user/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /user/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}