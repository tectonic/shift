<?php namespace Tests\Acceptance\Modules\Localisation\Repositories;

use App;
use Mockery;
use Tests\AcceptanceTestCase;
use Tectonic\Shift\Modules\Localisation\Repositories\DoctrineLocaleRepository;
use Tectonic\Shift\Modules\Localisation\Repositories\DoctrineLocalisationRepository;

class DoctrineLocalisationRepositoryTest extends AcceptanceTestCase
{

    /**
     * @var DoctrineLocaleRepository
     */
    protected $localeRepository;

    /**
     * @var DoctrineLocalisationRepository
     */
    protected $localisationRepository;

    /**
     * Data array complete with all required field to make a new locale or localisation
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

        Mockery::close(); // Destroy any existing mocks before creating new ones

        $this->cleanData['locales'] = [
            ['locale'  => 'English (Great Britain)', 'code' => 'en_GB'],
            ['locale'  => 'English (Australian)', 'code'    => 'en_AU'],
            ['locale'  => 'English (New Zealand)', 'code'   => 'en_NZ'],
            ['locale'  => 'English (United States)', 'code' => 'en_US'],
        ];

        $this->cleanData['localisations'] = [
            ['localeId' => 1, 'foreignId' => 1, 'resource' => 'Tectonic\Shift\CustomField', 'field' => 'label', 'value' => 'Custom field en_GB'],
            ['localeId' => 2, 'foreignId' => 1, 'resource' => 'Tectonic\Shift\CustomField', 'field' => 'label', 'value' => 'Custom field en_AU'],
            ['localeId' => 3, 'foreignId' => 1, 'resource' => 'Tectonic\Shift\CustomField', 'field' => 'label', 'value' => 'Custom field en_NZ'],
            ['localeId' => 4, 'foreignId' => 1, 'resource' => 'Tectonic\Shift\CustomField', 'field' => 'label', 'value' => 'Custom field en_US'],
        ];

        $this->localeRepository = App::make(DoctrineLocaleRepository::class);
        $this->localisationRepository = App::make(DoctrineLocalisationRepository::class);
    }

    public function testFindTranslationFetchesTranslation()
    {
        $this->createLocales();
        $this->createLocalisations();

        $result = $this->localisationRepository->findTranslation(1, 'Tectonic\Shift\CustomField', 'label', 'en_NZ');

        $this->assertEquals($this->cleanData['localisations'][2]['value'], $result['value']);
    }

    private function createLocales()
    {
        foreach($this->cleanData['locales'] as $locale)
        {
            $resource = $this->localeRepository->getNew($locale);
            $this->localeRepository->save($resource);
        }
    }

    private function createLocalisations()
    {
        foreach($this->cleanData['localisations'] as $localisation)
        {
            $resource = $this->localisationRepository->getNew($localisation);
            $this->localisationRepository->save($resource);
        }
    }


}
