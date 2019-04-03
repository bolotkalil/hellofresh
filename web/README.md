# This project contains 

* Recipes app REST API Endpoint 
* Hellofresh REST Micro-Framework

# Recipes endpoint description

Endpoint directory structures:
Recipe CRUD Controller localed in app/Controller/ directory     
Database migrations scripts located in app/Database/ directory     
Serialization, deserialization, hateoas for Recipes endpoint located in Entity/ directory      

**In order to launch application you should do this steps:**       
\#1 - Run containers with ```docker-compose up -d``` and enter into container using this command ```docker exec -it {container_id} /bin/bash```        
\#2 - Open root path(web/) of application and install dependencies of application with this command: ```composer install```          
\#3 - After installed and generated autoload script, you should create tables of the endpoint(recipes) in the root path run this command:                  
```./hellofresh migrations:migrate```            
\#4 - In order to get access into protected endpoints you should have bearer token, you can get the token using this command:             
       
_curl --request POST --url https://freshtesting.eu.auth0.com/oauth/token --header 'content-type: application/json' --data '{"client_id":"GVn40CvxntyXayc3tnHnim3xTOYrj3Gu","client_secret":"qooOn8xQOobMj4hVvrdtStR2-EVaefj1X1w5g6c2j0KoroayamqlNPWAtbjXAqSE","audience":"https://freshtesting.eu.auth0.com/api/v2/","grant_type":"client_credentials"}'_               

\#5 - Start using recipes endpoint.          
\#6 - API Documentation available here:          
[https://app.swaggerhub.com/apis-docs/bolotkalil/test-api/1.0.0](https://app.swaggerhub.com/apis-docs/bolotkalil/test-api/1.0.0)        

For testing endpoints use [Postman](http://www.getpostman.com/) like app.

**Note to get access into protected endpoint you should have access bearer token see step #4.**    


# Micro-Framework description

Hellofresh REST-like PHP Micro-Framework

It's based on the packages:

* [Guzzle/Psr7](https://github.com/guzzle/psr7)
* [Guzzle/Streams](https://github.com/guzzle/streams)
* [League\Container](https://github.com/thephpleague/container)
* [League\Container](https://github.com/thephpleague/container)
* [League\Route](https://github.com/thephpleague/route)
* [League\BooBoo](https://github.com/thephpleague/booboo)
* [Willdurand\Negotiation](https://github.com/willdurand/Negotiation)
* [Willdurand\Hateoas](https://github.com/willdurand/Hateoas)
* [Monolog\Monolog](https://github.com/Seldaek/monolog)
* [Symfony/Validator](https://github.com/symfony/validator)
* [Symfony/Dotenv](https://github.com/symfony/dotenv)
* [Symfony/Console](https://github.com/symfony/console)
* [Doctrine/Dbal](https://github.com/doctrine/dbal)
* [Doctrine/Migrations](https://github.com/doctrine/migrations)


# Micro-Framework features

* API Versioning
* Routing
* Dependency injection
* Error handling
* Serialization
* Deserialization
* HATEOAS
* Pagination
* Middleware
* Logging
* Validating
* Database abstraction
* Database migration

# Installation

Install it through composer.

```composer install```

# Usage

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Hellofresh\Config;
use Hellofresh\App;

# current API version, vendor name, debug
$config = new Config('0.1', 'vendor.name', true);

$app = new App($config);

$app->get('/{version:\d\.\d}', function (\GuzzleHttp\Psr7\ServerRequest $request) {
    return new Ok('Hello World from Hellofresh!');
});

$app->run();
```

### Configuration

Check the [Config](src/Config.php) class.

## API Versioning

By default the hellofresh works with API versions. 
This means that the [ApiVersion Middleware](src/Http/Middleware/ApiVersion.php) manipulates the incoming request. The version (based on the current Accept header) is added to the path.

---
* If Accept header is not parsable then Hellofresh throws a Not Acceptable exception

## Routing

For more information please visit [League/Route](https://github.com/thephpleague/route).

### Routing with arguments

```php
<?php
$app->get('/2.4/hello/{name:word}', function (Request $request, $params) {
    return new Ok('Hello ' . $params['name']);
});
```
### Routing with annotations

You have to register your controller.

```php
<?php

$app->registerController('\Foo\Bar\Controller\Index');
```

```php
<?php namespace Foo\Bar\Controller;
# Index.php

use Hellofresh\Http\Controller\BaseController;
use Hellofresh\Router\Annotation;

class Index extends BaseController
{
    /**
     * @Annotation(method="GET", path="/recipes/{id}", since=1.2, until=2.8)
     */
    public function get(Request $request, $params)
    {
        return new Ok('Hello World!');
    }
}
```

* ```since``` tag is optional
* ```until``` tag is optional


## Serialization, Deserialization, Hateoas

* Hellofresh can serialize your request based on the Accept header.
* Hellofresh can deserialize your content based on the Content-Type header.

### Serialization example

Let's see a Temperature entity:

*You do not have to use annotations! You can use configuration files! Browse in* [Jms\Serializer](http://jmsyst.com/libs/serializer) *and* [Willdurand\Hateoas](https://github.com/willdurand/Hateoas)

```php
<?php

namespace app\Entity\Recipes;

use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Serializer\XmlRoot("result")
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route("/recipes", parameters = {"id" = "expr(object.recipe_id)"}, absolute = false)
 * )
 */
class Created
{
    /**
     * @var integer
     * @Serializer\Type("integer")
     */
    public $recipe_id;

    /**
     * @var integer
     * @Serializer\Type("integer")
     */
    public $prep_time;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * @var integer
     * @Serializer\Type("integer")
     */
    public $difficulty;

    /**
     * @var boolean
     * @Serializer\Type("boolean")
     */
    public $vegetarian;

    /**
     * Created constructor.
     * @param integer $recipe_id
     * @param string $name
     * @param integer $prep_time
     * @param integer $difficulty
     * @param boolean $vegetarian
     */
    public function __construct(
        $recipe_id,
        $name,
        $prep_time,
        $difficulty,
        $vegetarian
    )
    {

        $this->recipe_id  = $recipe_id;
        $this->prep_time  = $prep_time;
        $this->name       = $name;
        $this->difficulty = $difficulty;
        $this->vegetarian = $vegetarian;
    }
}
```

The router:

```php
<?php
$app->post('/{version:\d\.\d}/recipes', function () use ($app, $params) {
    $request = new \GuzzleHttp\Psr7\ServerRequest();
    return $this->serialize(new \app\Entity\Recipes\Created(
                    1,
                    "Salad",
                    3,
                    3,
                    true
                ),
                    $request,
                    new Created("/recipes/1"));
});
```

Json response (Accept: application/vnd.vendor+json; version=1):

```json
{
    "recipe_id": 1,
    "name": "Salad",
    "prep_time": 3,
    "difficulty": 3,
    "vegetarian": true,
    "_links": {
        "self": {
            "href": "\/recipes\/1"
        }
    }
}
```

## CLI

You can use a helper script after a composer install (```hellofresh```).

With this helper script you can create app's tables with migrations command:
(```./hellofresh migrations:migrate```)

Before launch migration command make sure in .env file database credentials are correct

# Authentication

For this example I use Auth0([Auth0/Auth0-php](https://github.com/auth0/auth0-php)) service for JWT Authentication

### JWT Authentication

[Authentication Middleware](src/Http/Middleware/Authentication.php) take Authorization header Bearer token from incoming request. 

