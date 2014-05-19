<?php

namespace Tectonic\Shift\Library\Support;

use App;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Tectonic\Shift\Library\BaseValidator;
use Tectonic\Shift\Library\Response;
use Tectonic\Shift\Library\SqlBaseRepositoryInterface;

abstract class BaseController extends Controller
{
	/**
	 * Stores the full path to the search class to be used for search. The default search
	 * class is derived from conventions. The search class itself should sit inside the Search
	 * directory within a module.
	 *
	 * @var string
	 */
	public $searchClass;

    /**
     * The CRUDService stored represents the basic or base functionality for a given resource.
     *
     * @var CRUDService
     */
    public $crudService;

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$search = $this->resolveSearchClass();

		$search->setParams(Input::get());
        $search->execute();

		return $search->results();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postStore()
	{
        $this->crudService->create(Input::get());
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getShow($id)
	{
		return $this->crudService->get($id);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function putUpdate($id)
	{
        return $this->crudService->update($id, Input::get());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function deleteDestroy($id = null)
	{
        return $this->crudService->delete($id);
	}

	/**
	 * Returns the resolved search class.
	 *
	 * @return mixed
	 */
	protected function resolveSearchClass()
	{
		$className = $this->resolveSearchClassName($this->searchClass);

		$searchClass = App::make($className);

		return $searchClass;
	}

	/**
	 * Returns the name of the search class to be used for search execution.
	 *
	 * @param string $searchClass
	 * @return string
	 */
	protected function resolveSearchClassName($searchClass = null)
	{
		if (!is_null($searchClass)) return $searchClass;

		$class = get_class($this);
		$class = str_replace('Tectonic\Shift\Modules\\', '', $class);

		$classParts = explode('\\', $class);
		$module     = array_shift($classParts);
		$baseClass  = str_replace('Controller', '', array_pop($classParts)).'Search';

		$searchClassName = implode('\\', ['Tectonic\Shift\Modules', $module, 'Search', $baseClass]);

		return $searchClassName;
	}
}