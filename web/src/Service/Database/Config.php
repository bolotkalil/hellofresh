<?php
/**
 * Created by PhpStorm.
 * User: bolotkalil
 * Date: 10/21/18
 * Time: 10:35 AM
 */

namespace Hellofresh\Service\Database;


use Hellofresh\Contract\Service\IConfig;
use Symfony\Component\Dotenv\Dotenv;

class Config implements IConfig
{
    /**
     * @var array
     */
    public $params;

    /**
     * Config constructor.
     * @param $params
     */
    public function __construct($params=null)
    {

        if (!empty($params)) {

            $this->params = $params;

        } else {

            (new Dotenv())->load(dirname(dirname(dirname(__DIR__))).'/.env');

            $this->params = [
                'dbname'   => getenv('DB_NAME'),
                'user'     => getenv('DB_USER'),
                'password' => getenv('DB_PASS'),
                'host'     => getenv('DB_HOST'),
                'port'     => getenv('DB_PORT'),
                'driver'   => getenv('DB_DRIVER'),
                'charset'  => getenv('DB_CHARSET')
            ];

        }
    }

    /**
     * @return string
     */
    public static function getServiceName()
    {
        return 'database';
    }
}