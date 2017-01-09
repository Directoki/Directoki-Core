<?php


namespace DirectokiBundle\Tests\Controller;

use DirectokiBundle\Entity\Directory;
use DirectokiBundle\Entity\Event;
use DirectokiBundle\Entity\Field;
use DirectokiBundle\Entity\Project;
use DirectokiBundle\Entity\Record;
use DirectokiBundle\Entity\RecordHasFieldBooleanValue;
use DirectokiBundle\Entity\RecordHasFieldStringValue;
use DirectokiBundle\Entity\RecordHasFieldTextValue;
use DirectokiBundle\Entity\User;
use DirectokiBundle\FieldType\FieldTypeBoolean;
use DirectokiBundle\FieldType\FieldTypeString;
use DirectokiBundle\FieldType\FieldTypeText;
use DirectokiBundle\Tests\BaseTestWithDataBase;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 */
class RecordRepositoryWithDataBaseTest extends BaseTestWithDataBase
{


    function testNoRecordsEvenExist() {

        $user = new User();
        $user->setEmail('test1@example.com');
        $user->setPassword('password');
        $user->setUsername('test1');
        $this->em->persist($user);

        $project = new Project();
        $project->setTitle('test1');
        $project->setPublicId('test1');
        $project->setOwner($user);
        $this->em->persist($project);

        $event = new Event();
        $event->setProject($project);
        $event->setUser($user);
        $this->em->persist($event);

        $directory = new Directory();
        $directory->setPublicId('directory1');
        $directory->setTitle('Directory1');
        $directory->setProject($project);
        $directory->setCreationEvent($event);
        $this->em->persist($directory);

        $this->em->flush();

        # TEST

        $records = $this->em->getRepository('DirectokiBundle:Record')->getRecordsNeedingAttention($directory);
        $this->assertEquals(0, count($records));

    }

    function testRecordNotFound1() {

        $user = new User();
        $user->setEmail('test1@example.com');
        $user->setPassword('password');
        $user->setUsername('test1');
        $this->em->persist($user);

        $project = new Project();
        $project->setTitle('test1');
        $project->setPublicId('test1');
        $project->setOwner($user);
        $this->em->persist($project);

        $event = new Event();
        $event->setProject($project);
        $event->setUser($user);
        $this->em->persist($event);

        $directory = new Directory();
        $directory->setPublicId('directory1');
        $directory->setTitle('Directory1');
        $directory->setProject($project);
        $directory->setCreationEvent($event);
        $this->em->persist($directory);

        $record = new Record();
        $record->setDirectory($directory);
        $record->setCreationEvent($event);
        $this->em->persist($record);

        $this->em->flush();

        # TEST

        $records = $this->em->getRepository('DirectokiBundle:Record')->getRecordsNeedingAttention($directory);
        $this->assertEquals(0, count($records));

    }


    function testRecordNotFound2() {

        $user = new User();
        $user->setEmail('test1@example.com');
        $user->setPassword('password');
        $user->setUsername('test1');
        $this->em->persist($user);

        $project = new Project();
        $project->setTitle('test1');
        $project->setPublicId('test1');
        $project->setOwner($user);
        $this->em->persist($project);

        $event = new Event();
        $event->setProject($project);
        $event->setUser($user);
        $this->em->persist($event);

        $directory = new Directory();
        $directory->setPublicId('directory1');
        $directory->setTitle('Directory1');
        $directory->setProject($project);
        $directory->setCreationEvent($event);
        $this->em->persist($directory);

        $field = new Field();
        $field->setTitle('Title');
        $field->setPublicId('title');
        $field->setDirectory($directory);
        $field->setFieldType(FieldTypeString::FIELD_TYPE_INTERNAL);
        $field->setCreationEvent($event);
        $this->em->persist($field);

        $record = new Record();
        $record->setDirectory($directory);
        $record->setCreationEvent($event);
        $this->em->persist($record);

        $fieldStringHasValue = new RecordHasFieldStringValue();
        $fieldStringHasValue->setField($field);
        $fieldStringHasValue->setRecord($record);
        $fieldStringHasValue->setCreationEvent($event);
        $fieldStringHasValue->setValue('Mod me pls!');
        // This value is already approved so it shouldn't show
        $fieldStringHasValue->setApprovalEvent($event);
        $fieldStringHasValue->setApprovedAt(new \DateTime());
        $this->em->persist($fieldStringHasValue);

        $this->em->flush();

        # TEST

        $records = $this->em->getRepository('DirectokiBundle:Record')->getRecordsNeedingAttention($directory);
        $this->assertEquals(0, count($records));

    }



    function testFieldStringModerationNeeded() {

        $user = new User();
        $user->setEmail('test1@example.com');
        $user->setPassword('password');
        $user->setUsername('test1');
        $this->em->persist($user);

        $project = new Project();
        $project->setTitle('test1');
        $project->setPublicId('test1');
        $project->setOwner($user);
        $this->em->persist($project);

        $event = new Event();
        $event->setProject($project);
        $event->setUser($user);
        $this->em->persist($event);

        $directory = new Directory();
        $directory->setPublicId('directory1');
        $directory->setTitle('Directory1');
        $directory->setProject($project);
        $directory->setCreationEvent($event);
        $this->em->persist($directory);

        $field = new Field();
        $field->setTitle('Title');
        $field->setPublicId('title');
        $field->setDirectory($directory);
        $field->setFieldType(FieldTypeString::FIELD_TYPE_INTERNAL);
        $field->setCreationEvent($event);
        $this->em->persist($field);

        $record = new Record();
        $record->setDirectory($directory);
        $record->setCreationEvent($event);
        $this->em->persist($record);

        $fieldStringHasValue = new RecordHasFieldStringValue();
        $fieldStringHasValue->setField($field);
        $fieldStringHasValue->setRecord($record);
        $fieldStringHasValue->setCreationEvent($event);
        $fieldStringHasValue->setValue('Mod me pls!');
        $this->em->persist($fieldStringHasValue);

        $this->em->flush();

        # TEST

        $records = $this->em->getRepository('DirectokiBundle:Record')->getRecordsNeedingAttention($directory);
        $this->assertEquals(1, count($records));

    }

    function testFieldStringModerationAndDeDupeNeeded() {

        $user = new User();
        $user->setEmail('test1@example.com');
        $user->setPassword('password');
        $user->setUsername('test1');
        $this->em->persist($user);

        $project = new Project();
        $project->setTitle('test1');
        $project->setPublicId('test1');
        $project->setOwner($user);
        $this->em->persist($project);

        $event = new Event();
        $event->setProject($project);
        $event->setUser($user);
        $this->em->persist($event);

        $directory = new Directory();
        $directory->setPublicId('directory1');
        $directory->setTitle('Directory1');
        $directory->setProject($project);
        $directory->setCreationEvent($event);
        $this->em->persist($directory);

        $field = new Field();
        $field->setTitle('Title');
        $field->setPublicId('title');
        $field->setDirectory($directory);
        $field->setFieldType(FieldTypeString::FIELD_TYPE_INTERNAL);
        $field->setCreationEvent($event);
        $this->em->persist($field);

        $record = new Record();
        $record->setDirectory($directory);
        $record->setCreationEvent($event);
        $this->em->persist($record);

        $fieldStringHasValue1 = new RecordHasFieldStringValue();
        $fieldStringHasValue1->setField($field);
        $fieldStringHasValue1->setRecord($record);
        $fieldStringHasValue1->setCreationEvent($event);
        $fieldStringHasValue1->setValue('Mod me pls!');
        $this->em->persist($fieldStringHasValue1);

        $fieldStringHasValue2 = new RecordHasFieldStringValue();
        $fieldStringHasValue2->setField($field);
        $fieldStringHasValue2->setRecord($record);
        $fieldStringHasValue2->setCreationEvent($event);
        $fieldStringHasValue2->setValue('But I am spam');
        $this->em->persist($fieldStringHasValue2);

        $this->em->flush();

        # TEST

        $records = $this->em->getRepository('DirectokiBundle:Record')->getRecordsNeedingAttention($directory);
        $this->assertEquals(1, count($records));

    }


    function testFieldTextModerationNeeded() {

        $user = new User();
        $user->setEmail('test1@example.com');
        $user->setPassword('password');
        $user->setUsername('test1');
        $this->em->persist($user);

        $project = new Project();
        $project->setTitle('test1');
        $project->setPublicId('test1');
        $project->setOwner($user);
        $this->em->persist($project);

        $event = new Event();
        $event->setProject($project);
        $event->setUser($user);
        $this->em->persist($event);

        $directory = new Directory();
        $directory->setPublicId('directory1');
        $directory->setTitle('Directory1');
        $directory->setProject($project);
        $directory->setCreationEvent($event);
        $this->em->persist($directory);

        $field = new Field();
        $field->setTitle('Description');
        $field->setPublicId('description');
        $field->setDirectory($directory);
        $field->setFieldType(FieldTypeText::FIELD_TYPE_INTERNAL);
        $field->setCreationEvent($event);
        $this->em->persist($field);

        $record = new Record();
        $record->setDirectory($directory);
        $record->setCreationEvent($event);
        $this->em->persist($record);

        $fieldTextHasValue = new RecordHasFieldTextValue();
        $fieldTextHasValue->setField($field);
        $fieldTextHasValue->setRecord($record);
        $fieldTextHasValue->setCreationEvent($event);
        $fieldTextHasValue->setValue('Mod me pls!');
        $this->em->persist($fieldTextHasValue);

        $this->em->flush();

        # TEST

        $records = $this->em->getRepository('DirectokiBundle:Record')->getRecordsNeedingAttention($directory);
        $this->assertEquals(1, count($records));

    }

    function testFieldTextAndStringModerationNeeded() {

        $user = new User();
        $user->setEmail('test1@example.com');
        $user->setPassword('password');
        $user->setUsername('test1');
        $this->em->persist($user);

        $project = new Project();
        $project->setTitle('test1');
        $project->setPublicId('test1');
        $project->setOwner($user);
        $this->em->persist($project);

        $event = new Event();
        $event->setProject($project);
        $event->setUser($user);
        $this->em->persist($event);

        $directory = new Directory();
        $directory->setPublicId('directory1');
        $directory->setTitle('Directory1');
        $directory->setProject($project);
        $directory->setCreationEvent($event);
        $this->em->persist($directory);

        $fieldTitle = new Field();
        $fieldTitle->setTitle('Title');
        $fieldTitle->setPublicId('title');
        $fieldTitle->setDirectory($directory);
        $fieldTitle->setFieldType(FieldTypeString::FIELD_TYPE_INTERNAL);
        $fieldTitle->setCreationEvent($event);
        $this->em->persist($fieldTitle);
        
        $fieldDesc = new Field();
        $fieldDesc->setTitle('Description');
        $fieldDesc->setPublicId('description');
        $fieldDesc->setDirectory($directory);
        $fieldDesc->setFieldType(FieldTypeText::FIELD_TYPE_INTERNAL);
        $fieldDesc->setCreationEvent($event);
        $this->em->persist($fieldDesc);

        $record = new Record();
        $record->setDirectory($directory);
        $record->setCreationEvent($event);
        $this->em->persist($record);

        $fieldStringHasValue = new RecordHasFieldStringValue();
        $fieldStringHasValue->setField($fieldTitle);
        $fieldStringHasValue->setRecord($record);
        $fieldStringHasValue->setCreationEvent($event);
        $fieldStringHasValue->setValue('Mod me pls! Title');
        $this->em->persist($fieldStringHasValue);

        $fieldTextHasValue = new RecordHasFieldTextValue();
        $fieldTextHasValue->setField($fieldDesc);
        $fieldTextHasValue->setRecord($record);
        $fieldTextHasValue->setCreationEvent($event);
        $fieldTextHasValue->setValue('Mod me pls! Desc');
        $this->em->persist($fieldTextHasValue);

        $this->em->flush();

        # TEST

        $records = $this->em->getRepository('DirectokiBundle:Record')->getRecordsNeedingAttention($directory);
        $this->assertEquals(1, count($records));

    }



    function testFieldBooleanModerationNeeded() {

        $user = new User();
        $user->setEmail('test1@example.com');
        $user->setPassword('password');
        $user->setUsername('test1');
        $this->em->persist($user);

        $project = new Project();
        $project->setTitle('test1');
        $project->setPublicId('test1');
        $project->setOwner($user);
        $this->em->persist($project);

        $event = new Event();
        $event->setProject($project);
        $event->setUser($user);
        $this->em->persist($event);

        $directory = new Directory();
        $directory->setPublicId('directory1');
        $directory->setTitle('Directory1');
        $directory->setProject($project);
        $directory->setCreationEvent($event);
        $this->em->persist($directory);

        $field = new Field();
        $field->setTitle('Cafe');
        $field->setPublicId('is_cafe');
        $field->setDirectory($directory);
        $field->setFieldType(FieldTypeBoolean::FIELD_TYPE_INTERNAL);
        $field->setCreationEvent($event);
        $this->em->persist($field);

        $record = new Record();
        $record->setDirectory($directory);
        $record->setCreationEvent($event);
        $this->em->persist($record);

        $fieldBooleanHasValue = new RecordHasFieldBooleanValue();
        $fieldBooleanHasValue->setField($field);
        $fieldBooleanHasValue->setRecord($record);
        $fieldBooleanHasValue->setCreationEvent($event);
        $fieldBooleanHasValue->setValue(true);
        $this->em->persist($fieldBooleanHasValue);

        $this->em->flush();

        # TEST

        $records = $this->em->getRepository('DirectokiBundle:Record')->getRecordsNeedingAttention($directory);
        $this->assertEquals(1, count($records));

    }


}

