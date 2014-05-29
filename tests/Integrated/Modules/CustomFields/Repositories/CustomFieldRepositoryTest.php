<?php namespace Tests\Integrated\Modules\CustomFields\Repositories;

use Mockery;
use Tests\TestCase;
use Tectonic\Shift\Modules\CustomFields\Models\CustomField;
use Tectonic\Shift\Modules\CustomFields\Repositories\SqlCustomFieldRepository;

class CustomFieldRepositoryTest extends TestCase
{

    /**
     * @var SqlCustomFieldRepository
     */
    protected $repository;

    /**
     * Data array complete with all required field to make a new CustomField
     *
     * @var array
     */
    protected $cleanData;

    /**
     * Setup method to run before each test
     */
    public function setUp()
    {
        parent::setUp();

        $this->cleanData = [
            'resource'     => 'user',
            'type'         => 'text',
            'field_title'  => 'Custom field title 1',
            'field_code'   => 'CFT',
            'label'        => '',
            'options'      => '',
            'validation'   => '',
            'settings'     => '',
            'required'     => true,
            'registration' => true,
            'order'        => 1
        ];

        $this->repository = new SqlCustomFieldRepository(new CustomField());

        Mockery::close(); // Destroy any existing mocks before creating new ones
    }

    /**
     * Test repository calls the correct methods on CREATE operation.
     *
     * @test
     */
    public function testRepositoryPerformsCreateOperations()
    {
        // Arrange
        $model = Mockery::mock('Tectonic\Shift\Modules\CustomFields\Models\CustomField');
        $model->shouldReceive('newInstance')
            ->with($this->cleanData)
            ->once()
            ->andReturn($model);
        $repository = new SqlCustomFieldRepository($model);

        // Act
        $newModel   = $repository->getNew($this->cleanData);

        // Assert
        $this->assertSame($model, $newModel);
    }

    /**
     * Test repository can READ a record by id.
     *
     * @test
     */
    public function testRepositoryPerformsReadOperations()
    {
        // Arrange
        $customField = CustomField::create($this->cleanData);
        $repository  = new SqlCustomFieldRepository(new CustomField);

        // Act
        $result = $repository->getById($customField->id);

        // Assert
        $this->assertSame((int)$customField->id, (int)$result->id);
        $this->assertSame($customField->field_title, $result->field_title);
    }

    /**
     * Test repository throws exception when trying to find a record by id
     * that does NOT exist.
     *
     * @test
     */
    public function testRepositoryThrowsExceptionWhenFindingNonExistentCustomField()
    {
        // Arrange
        $model = new CustomField();
        $repository = new SqlCustomFieldRepository($model);
        $idToFind = 1001; // A record with this ID does not exist

        // Act
        $result = $repository->getById($idToFind);

        // Assert
        $this->assertEmpty($result);
        $this->setExpectedException('Illuminate\Database\Eloquent\ModelNotFoundException');
        $repository->requireById($idToFind);
    }

    /**
     * Test repository performs UPDATE on an existing record.
     *
     * @test
     */
    public function testRepositoryPerformsUpdateOperation()
    {
        // Arrange
        $this->populateCustomFieldTable();
        $resource = $this->repository->getById(1);
        $updateData = [ 'resource' => 'user', 'type' => 'checkbox' ];

        // Act
        $result = $this->repository->update($resource, $updateData);

        // Assert
        //$this->assertNotSame($resource->resource, $result->resource);
    }

    /**
     * Helper: populate custom_field table with an entry for testing.
     *
     * Result: 1 CustomField record with an ID:1
     */
    protected function populateCustomFieldTable()
    {
        CustomField::create($this->cleanData);
    }
}
