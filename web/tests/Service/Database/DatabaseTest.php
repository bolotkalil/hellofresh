<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/21/18
 * Time: 11:07 AM
 */

namespace Hellofresh\Service\Database;

use Doctrine\DBAL\Driver\PDOException;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Platforms\PostgreSQL91Platform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use League\Container\Container;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    /**
     * @var DriverManager
     */
    private $db;

    /**
     * @var Container
     */
    private $container;

    public function setUp()
    {
        $config = new Config();

        $this->container = new Container();
        (new Service())->register($this->container, $config);

        $this->db = $this->container->get($config::getServiceName());

    }

    public function testDriver()
    {
        $this->assertInstanceOf('Doctrine\DBAL\Platforms\PostgreSqlPlatform', $this->db->getDatabasePlatform());
    }
}
