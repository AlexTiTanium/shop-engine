<?php

namespace ODM\Hydrators;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class DocumentsUsersSessionStorageHydrator implements HydratorInterface
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
        if (isset($data['sid'])) {
            $value = $data['sid'];
            $return = (string) $value;
            $this->class->reflFields['sid']->setValue($document, $return);
            $hydratedData['sid'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['user_id'])) {
            $value = $data['user_id'];
            $return = (string) $value;
            $this->class->reflFields['user_id']->setValue($document, $return);
            $hydratedData['user_id'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['ip'])) {
            $value = $data['ip'];
            $return = (string) $value;
            $this->class->reflFields['ip']->setValue($document, $return);
            $hydratedData['ip'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['now'])) {
            $value = $data['now'];
            $return = (string) $value;
            $this->class->reflFields['now']->setValue($document, $return);
            $hydratedData['now'] = $return;
        }

        /** @Field(type="boolean") */
        if (isset($data['remember_me'])) {
            $value = $data['remember_me'];
            $return = (bool) $value;
            $this->class->reflFields['remember_me']->setValue($document, $return);
            $hydratedData['remember_me'] = $return;
        }

        /** @Field(type="int") */
        if (isset($data['exp_session'])) {
            $value = $data['exp_session'];
            $return = (int) $value;
            $this->class->reflFields['exp_session']->setValue($document, $return);
            $hydratedData['exp_session'] = $return;
        }

        /** @Field(type="int") */
        if (isset($data['exp_online'])) {
            $value = $data['exp_online'];
            $return = (int) $value;
            $this->class->reflFields['exp_online']->setValue($document, $return);
            $hydratedData['exp_online'] = $return;
        }

        /** @ReferenceOne */
        if (isset($data['user'])) {
            $reference = $data['user'];
            if (isset($this->class->fieldMappings['user']['simple']) && $this->class->fieldMappings['user']['simple']) {
                $className = $this->class->fieldMappings['user']['targetDocument'];
                $mongoId = $reference;
            } else {
                $className = $this->dm->getClassNameFromDiscriminatorValue($this->class->fieldMappings['user'], $reference);
                $mongoId = $reference['$id'];
            }
            $targetMetadata = $this->dm->getClassMetadata($className);
            $id = $targetMetadata->getPHPIdentifierValue($mongoId);
            $return = $this->dm->getReference($className, $id);
            $this->class->reflFields['user']->setValue($document, $return);
            $hydratedData['user'] = $return;
        }
        return $hydratedData;
    }
}