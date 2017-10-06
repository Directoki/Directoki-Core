<?php


namespace DirectokiBundle\Tests\Action;


use DirectokiBundle\Action\ChangeFieldTypeStringToStringWithLocale;
use DirectokiBundle\Action\ChangeFieldTypeStringToText;
use DirectokiBundle\Action\DeleteOldInformation;
use DirectokiBundle\Entity\Directory;
use DirectokiBundle\Entity\Event;
use DirectokiBundle\Entity\Field;
use DirectokiBundle\Entity\Locale;
use DirectokiBundle\Entity\Project;
use DirectokiBundle\Entity\Record;
use DirectokiBundle\Entity\RecordHasFieldStringValue;
use DirectokiBundle\Entity\RecordHasState;
use DirectokiBundle\FieldType\FieldTypeString;
use DirectokiBundle\FieldType\FieldTypeStringWithLocale;
use DirectokiBundle\FieldType\FieldTypeText;
use DirectokiBundle\Tests\BaseTestWithDataBase;
use DirectokiBundle\Tests\MockTimeService;
use JMBTechnology\UserAccountsBundle\Entity\User;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 */
class DeleteOldInformationWithDataBaseTest extends BaseTestWithDataBase
{

    public function test1()
    {

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

        $event1 = new Event();
        $event1->setIP('1.2.3.4');
        $event1->setUserAgent('Great Browser');
        $event1->setProject($project);
        $event1->setCreatedAt(new \DateTime('2017-01-01 10:00:00', new \DateTimeZone('UTC')));
        $this->em->persist($event1);

        $event2 = new Event();
        $event2->setIP('5.6.7.8');
        $event2->setUserAgent('Great Browser');
        $event2->setProject($project);
        $event2->setCreatedAt(new \DateTime('2017-11-01 10:00:00', new \DateTimeZone('UTC')));
        $this->em->persist($event2);

        $this->em->flush();

        // We are at this point relying on the default value of directoki.delete_information_after_hours being 6 months.
        // Not great for a robust test.

        $this->container->set('directoki.time_service',
            new MockTimeService(new \DateTime('2017-12-01 10:00:00', new \DateTimeZone('UTC'))));

        $action = new DeleteOldInformation($this->container);
        $action->go();

        $events = $this->em->getRepository('DirectokiBundle:Event')->findAll(array(), array('createdAt'=>'asc'));

        var_dump($events[0]->getIP());
        var_dump($events[1]->getIP());

        $this->assertNull($events[0]->getUserAgent());
        $this->assertNull($events[0]->getIP());

        $this->assertEquals('Great Browser',$events[1]->getUserAgent());
        $this->assertEquals('1.2.3.4', $events[1]->getIP());

    }

}


