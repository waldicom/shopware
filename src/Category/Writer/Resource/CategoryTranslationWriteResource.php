<?php declare(strict_types=1);

namespace Shopware\Category\Writer\Resource;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Write\Field\FkField;
use Shopware\Framework\Write\Field\LongTextField;
use Shopware\Framework\Write\Field\ReferenceField;
use Shopware\Framework\Write\Field\StringField;
use Shopware\Framework\Write\Flag\Required;
use Shopware\Framework\Write\WriteResource;

class CategoryTranslationWriteResource extends WriteResource
{
    protected const NAME_FIELD = 'name';
    protected const PATH_NAMES_FIELD = 'pathNames';
    protected const META_KEYWORDS_FIELD = 'metaKeywords';
    protected const META_TITLE_FIELD = 'metaTitle';
    protected const META_DESCRIPTION_FIELD = 'metaDescription';
    protected const CMS_HEADLINE_FIELD = 'cmsHeadline';
    protected const CMS_DESCRIPTION_FIELD = 'cmsDescription';

    public function __construct()
    {
        parent::__construct('category_translation');

        $this->fields[self::NAME_FIELD] = (new StringField('name'))->setFlags(new Required());
        $this->fields[self::PATH_NAMES_FIELD] = new LongTextField('path_names');
        $this->fields[self::META_KEYWORDS_FIELD] = new LongTextField('meta_keywords');
        $this->fields[self::META_TITLE_FIELD] = new StringField('meta_title');
        $this->fields[self::META_DESCRIPTION_FIELD] = new LongTextField('meta_description');
        $this->fields[self::CMS_HEADLINE_FIELD] = new StringField('cms_headline');
        $this->fields[self::CMS_DESCRIPTION_FIELD] = new LongTextField('cms_description');
        $this->fields['category'] = new ReferenceField('categoryUuid', 'uuid', \Shopware\Category\Writer\Resource\CategoryWriteResource::class);
        $this->primaryKeyFields['categoryUuid'] = (new FkField('category_uuid', \Shopware\Category\Writer\Resource\CategoryWriteResource::class, 'uuid'))->setFlags(new Required());
        $this->fields['language'] = new ReferenceField('languageUuid', 'uuid', \Shopware\Shop\Writer\Resource\ShopWriteResource::class);
        $this->primaryKeyFields['languageUuid'] = (new FkField('language_uuid', \Shopware\Shop\Writer\Resource\ShopWriteResource::class, 'uuid'))->setFlags(new Required());
    }

    public function getWriteOrder(): array
    {
        return [
            \Shopware\Category\Writer\Resource\CategoryWriteResource::class,
            \Shopware\Shop\Writer\Resource\ShopWriteResource::class,
            \Shopware\Category\Writer\Resource\CategoryTranslationWriteResource::class,
        ];
    }

    public static function createWrittenEvent(array $updates, TranslationContext $context, array $errors = []): \Shopware\Category\Event\CategoryTranslationWrittenEvent
    {
        $event = new \Shopware\Category\Event\CategoryTranslationWrittenEvent($updates[self::class] ?? [], $context, $errors);

        unset($updates[self::class]);

        if (!empty($updates[\Shopware\Category\Writer\Resource\CategoryWriteResource::class])) {
            $event->addEvent(\Shopware\Category\Writer\Resource\CategoryWriteResource::createWrittenEvent($updates, $context));
        }
        if (!empty($updates[\Shopware\Shop\Writer\Resource\ShopWriteResource::class])) {
            $event->addEvent(\Shopware\Shop\Writer\Resource\ShopWriteResource::createWrittenEvent($updates, $context));
        }
        if (!empty($updates[\Shopware\Category\Writer\Resource\CategoryTranslationWriteResource::class])) {
            $event->addEvent(\Shopware\Category\Writer\Resource\CategoryTranslationWriteResource::createWrittenEvent($updates, $context));
        }

        return $event;
    }
}