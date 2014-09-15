<?php namespace Tectonic\Shift\Library\Support\Database\Doctrine;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tectonic\Shift\Library\Support\Database\RecordNotFoundException;
use Tectonic\Shift\Library\Support\Database\RepositoryInterface;

abstract class Repository extends EntityRepository implements RepositoryInterface
{
    /**
     * The entity that this repository will use for the base level queries.
     *
     * @var string null
     */
    protected $entity;

	/**
	 * Stores the EntityManager class that is used for the queries.
	 *
	 * @var EntityManager
	 */
	protected $entityManager;

	/**
	 * If a repository is restricted by account, then the default methods will ensure
	 * that the queries in the parent class are at least using the current account
	 * for the user to restrict the query by.
	 *
	 * @var bool
	 */
	protected $restrictByAccount = true;

    /**
     * Some simple validation on the class implementation.
     *
     * @throws EntityIsNullException
     */
    public function __construct(EntityManager $entityManager)
    {
		$this->entityManager = $entityManager;

	    if (is_null($this->entity)) {
            throw new EntityIsNullException;
        }
    }

    /**
     * Returns the Doctrine entity manager.
     *
     * @return mixed
     */
    public function entityManager()
    {
        return $this->entityManager;
    }

    /**
     * Get a specific resource.
     *
     * @param integer $id
     *
     * @return Resource
     */
    public function getById($id)
    {
	    return $this->getBy('id', $id);
    }

    /**
     * Get a specific resource identified by slug.
     *
     * @param string $slug
     * @return Resource
     */
    public function getBySlug($slug)
    {
        return $this->getBy('slug', $slug);
    }

    /**
     * Get a resource.
     *
     * @param $fieldName
     * @param $parameter
     * @return Resource
     */
    public function getBy($fieldName, $parameter)
    {
        $queryBuilder = $this->createQuery();
        $queryBuilder->andWhere($this->field($fieldName)." = :".$fieldName);
        $queryBuilder->setParameter($fieldName, $parameter);

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

	/**
	 * Generates the field based on the alias provided by the entity abbreviation method.
	 *
	 * @param string $field
	 * @return string
	 */
	public function field($field)
	{
		return $this->entityAbbreviation().'.'.$field;
	}

    /**
     * Searches for a resource with the id provided. If no resource is found that matches
     * the $id value, then it will throw a RecordNotFoundException.
     *
     * @param $id
     *
     * @return Resource
     * @throws RecordNotFoundException
     */
    public function requireById($id)
    {
        $model = $this->getById($id);

        if (!$model) {
            throw with(new RecordNotFoundException($this->entity, $id));
        }

        return $model;
    }

    /**
     * Searches for a resource with the slug provided. If no resource is found matching
     * the $slug provided, then it will throw a RecordNotFoundException.
     *
     * @param $slug
     *
     * @return Resource
     * @throws mixed
     */
    public function requireBySlug($slug)
    {
        $model = $this->getBySlug($slug);

        if(!$model) {
            throw with(new RecordNotFoundException($this->entity, $slug));
        }

        return $model;
    }

    /**
     * Create a resource based on the data provided.
     *
     * @param array $data
     * @return Resource
     */
    public function getNew(array $data = [])
    {
        $entity = (new $this->entity);

        if ($data) {
            $this->decorate($entity, $data);
        }

        return $entity;
    }

    /**
     * Delete a specific resource. Returns the resource that was deleted.
     *
     * @param object  $resource
     * @param boolean $permanent
     *
     * @return Resource
     */
    public function delete($resource, $permanent = false)
    {
        if ($permanent) {
            $this->entityManager()->remove($resource);
        }
        else {
            $this->decorate($resource, ['deletedAt' => new DateTime()]);
            $this->entityManager()->persist($resource);
        }

        // In order to hard-delete or soft-delete we need to invoke flush.
        $this->entityManager()->flush();

        return $resource;
    }

    /**
     * Update a resource based on the id and data provided.
     *
     * @param object $resource
     * @param array  $data
     *
     * @return Resource
     */
    public function update($resource, $data = [])
    {
        if ($data) {
            $this->decorate($resource, $data);
        }

        $this->save($resource);

        return $resource;
    }

    /**
     * Saves the resource provided to the database.
     *
     * @param $resource
     *
     * @return Resource
     */
    public function save($resource)
    {
        $this->entityManager()->persist($resource);
        $this->entityManager()->flush();

        return $resource;
    }

    /**
     * Save a number of resources at once. This is especially good with Doctrine as it allows
     * us to send a batch save instead of persisting and flushing resources one-by-one.
     *
     * @param $resources
     * @throws Exception
     * @return mixed|void
     */
    public function saveAll(...$resources)
    {
        if (count($resources) == 0) {
            throw new Exception('You must provide at least one $resource argument.');
        }

        foreach ($resources as $resource) {
            $this->entityManager()->persist($resource);
        }

        $this->entityManager()->flush();
    }

	/**
	 * Creates a new query object and utilises the account restriction feature.
	 *
	 * @return mixed
	 */
	protected function createQuery()
	{
		$abbr = $this->entityAbbreviation();
		$queryBuilder = $this->entityManager()->createQueryBuilder();

		$queryBuilder->select([$abbr]);
		$queryBuilder->from($this->entity, $abbr);

		if ($this->restrictByAccount) {
			/* @TODO: get the actual account id for the current request */
			$queryBuilder->where($this->field('accountId').' = :accountId');
            $queryBuilder->setParameter('accountId', 1);
		}

		return $queryBuilder;
	}

	/**
	 * Returns an abbreviation of the entity's name. This is simply the first character, in
	 * a lower case format, used for doctrine queries.
	 *
	 * @return string
	 */
	protected function entityAbbreviation()
	{
		return strtolower(substr($this->entity, 0, 1));
	}

    /**
     * Used to decorate a resource with the required data for persisting.
     *
     * @param $resource
     * @param $data
     * @return mixed
     */
    private function decorate($resource, $data)
    {
        foreach ($data as $key => $value) {
            $resource->{'set'.$key}($value);
        }

        return $resource;
    }
}
