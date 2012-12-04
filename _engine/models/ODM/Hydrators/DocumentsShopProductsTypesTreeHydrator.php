<?php

namespace ODM\Hydrators;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class DocumentsShopProductsTypesTreeHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data, array $hints = array())
    {
        $hydratedData = array();

        /** @Field(type="id") */
        if (isset($data['_id'])) {
            $value = $data['_id'];
            $return = $value instanceof \MongoId ? (string) $value : $value;
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['name'])) {
            $value = $data['name'];
            $return = (string) $value;
            $this->class->reflFields['name']->setValue($document, $return);
            $hydratedData['name'] = $return;
        }

        /** @Field(type="int") */
        if (isset($data['index'])) {
            $value = $data['index'];
            $return = (int) $value;
            $this->class->reflFields['index']->setValue($document, $return);
            $hydratedData['index'] = $return;
        }

        /** @Field(type="boolean") */
        if (isset($data['leaf'])) {
            $value = $data['leaf'];
            $return = (bool) $value;
            $this->class->reflFields['leaf']->setValue($document, $return);
            $hydratedData['leaf'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['parentId'])) {
            $value = $data['parentId'];
            $return = (string) $value;
            $this->class->reflFields['parentId']->setValue($document, $return);
            $hydratedData['parentId'] = $return;
        }

        /** @Many */
        $mongoData = isset($data['children']) ? $data['children'] : null;
        $return = new \Doctrine\ODM\MongoDB\PersistentCollection(new \Doctrine\Common\Collections\ArrayCollection(), $this->dm, $this->unitOfWork, '$');
        $return->setHints($hints);
        $return->setOwner($document, $this->class->fieldMappings['children']);
        $return->setInitialized(false);
        if ($mongoData) {
            $return->setMongoData($mongoData);
        }
        $this->class->reflFields['children']->setValue($document, $return);
        $hydratedData['children'] = $return;
        return $hydratedData;
    }
}