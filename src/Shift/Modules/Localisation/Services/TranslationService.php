<?php
namespace Tectonic\Shift\Modules\Localisation\Services;

use Tectonic\Localisation\Translator\Translatable;
use Tectonic\Localisation\Contracts\TranslationRepositoryInterface;

/**
 * Class TranslationService
 *
 * This service manages the creation, updating and removal of translations based on resources and
 * input from the client-side. It is far more specific than the TranslationManagementService, which deals
 * with individual translation records, regardless of relativity to any other resource on the system.
 *
 * Basically it provides a much tighter implementation closer to the business requirements of Shift, depending
 * on specific classes and objects rather than arrays and primitive values.
 *
 * @package Tectonic\Shift\Modules\Localisation\Services
 */
class TranslationService
{
    /**
     * @var TranslationRepositoryInterface
     */
    private $translationRepository;

    /**
     * @param TranslationRepositoryInterface $translationRepository
     */
    function __construct(TranslationRepositoryInterface $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    /**
     * Adds a new translation record based on the language, the translatable resource, and the field and value.
     *
     * @param Translatable $resource
     * @param string $language
     * @param string $field
     * @param mixed $value
     * @return TranslationInterface
     */
    public function add(TranslatableInterface $resource, $language, $field, $value)
    {
        $translation = $this->translationRepository->getNew();

        $translation->language = $language;
        $translation->foreign_id = $resource->getId();
        $translation->resource = $resource->getResourceName();
        $translation->field = $field;
        $translation->value = $value;

        return $this->translationRepository->save($translation);
    }

    /**
     * @param string $field
     * @param string $resource
     * @param int|null $foreignId
     */
    public function get($field, $resource, $foreignId = null)
    {
        $params = compact($field, $resource);

        if (!is_null($foreignId)) {
            $params['foreignId'] = $foreignId;
        }

        return $this->translationRepository->getByCriteria($params);
    }

    /**
     * Updates all translations based on the array provided. The provided array should have been one given
     * by the client, in the following format:
     *
     * ['account' => [
     *     'name' => [
     *         'en_GB' => 'My account name in english great britain',
     *         'en_US' => 'My account name for new yorkers'
     *     ]
     * ]
     *
     * @param $translations
     */
    public function updateAll($translations)
    {

    }
}
