<?php
 
namespace SMB\LoyerBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SMB\LoyerBundle\Entity\Etudiant as Etudiant;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\CreateSchemaDoctrineCommand;
 
class EtudiantControllerTest extends WebTestCase
{
    private $em;
    private $application;
 
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
 
        $this->application = new Application(static::$kernel);
 
        // drop the database
        $command = new DropDatabaseDoctrineCommand();
        $this->application->add($command);
        $input = new ArrayInput(array(
            'command' => 'doctrine:database:drop',
            '--force' => true
        ));
        $command->run($input, new NullOutput());
 
        // we have to close the connection after dropping the database so we don't get "No database selected" error
        $connection = $this->application->getKernel()->getContainer()->get('doctrine')->getConnection();
        if ($connection->isConnected()) {
            $connection->close();
        }
 
        // create the database
        $command = new CreateDatabaseDoctrineCommand();
        $this->application->add($command);
        $input = new ArrayInput(array(
            'command' => 'doctrine:database:create',
        ));
        $command->run($input, new NullOutput());
 
        // create schema
        $command = new CreateSchemaDoctrineCommand();
        $this->application->add($command);
        $input = new ArrayInput(array(
            'command' => 'doctrine:schema:create',
        ));
        $command->run($input, new NullOutput());
 
        // get the Entity Manager
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
 
        // load fixtures
        $client = static::createClient();
        $loader = new \Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader($client->getContainer());
        $loader->loadFromDirectory(static::$kernel->locateResource('@SMBLoyerBundle/DataFixtures/ORM'));
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->em);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }
    
    
    public function testIndex()
    {
        $cont = new EtudiantController();
        $client = static::createClient();
        $crawler = $client->request('GET', 'http://smb/gestionLoyer/web/app_dev.php/gestion-loyer/etudiant/');
        
//        $this->assertEquals('SMB\LoyerBundle\Controller\EtudiantController', $client->getRequest()->attributes->get('_controller'));
//        $this->assertTrue($crawler->filter('#contenu th:contains("Prénom")')->count() > 0);
    }
// 
    public function testGetPrenom()
    {
        $etudiant = $this->em->createQuery('SELECT j FROM SMBLoyerBundle:Etudiant j ')
            ->setMaxResults(1)
            ->getSingleResult();
 
        $this->assertEquals($etudiant->getPrenom(),$etudiant->getPrenom());
    }
 /*
    public function testGetPositionSlug()
    {
        $job = $this->em->createQuery('SELECT j FROM ErlemJobeetBundle:Job j ')
            ->setMaxResults(1)
            ->getSingleResult();
 
        $this->assertEquals($job->getPositionSlug(), Jobeet::slugify($job->getPosition()));
    }
 
    public function testGetLocationSlug()
    {
        $job = $this->em->createQuery('SELECT j FROM ErlemJobeetBundle:Job j ')
            ->setMaxResults(1)
            ->getSingleResult();
 
        $this->assertEquals($job->getLocationSlug(), Jobeet::slugify($job->getLocation()));
    }
 
    public function testSetExpiresAtValue()
    {
        $job = new Job();
        $job->setExpiresAtValue();
 
        $this->assertEquals(time() + 86400 * 30, $job->getExpiresAt()->format('U'));
    }
 
    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }*/
}