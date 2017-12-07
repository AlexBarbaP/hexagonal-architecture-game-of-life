# 1. Architecture

## 1.1. Hexagonal Architecture

Homework architecture is based on *Alistair Cockburn* 
[Hexagonal Architecture](http://alistair.cockburn.us/Hexagonal+architecture), also known as *'Ports and Adapters 
Architecture'*.

![Image](http://alistair.cockburn.us/get/2301)

It is also highly influenced by *Chris Fidao* job in [Hexagonal Architecture](http://fideloper.com/hexagonal-architecture)
and *Matthias Noback* Post Series [Layers, ports and adapters](https://matthiasnoback.nl/2017/08/layers-ports-and-adapters-part-2-layers/).

Hexagonal Architecture aims for business logic decoupling from its context of use, being this either a Web Framework, a 
cronjob script, a REST Api or a Test Case.

The way to get this done is by focusing on the business logic implementation without this one knowing nothing about the 
outside world implementation. Business logic can not depend on specific APIs or database access implementations, it just 
defines Port Interfaces that specific implementations must to agree on (Adapters) so business logic is decoupled from,
ad example, the Web framework, so it should be easy to update it or to migrate to another one. 

To make extremely evident the architecture, its layers and components every folder and file names have been suffixed 
with the element they represent, being those layers, ports, adapters or design patterns. In a real application some of 
these names could be simplified.

## 1.2. Layers

Architecture is based on three main layers:

* **Domain**
This is the internal more layer. It contains the core business logic of the application and it can NOT depend on the 
other, more external, layer. It can only depend on itself.
To be able to communicate to the more external layers, and the outside world, Domain layer defines *Ports*. Ports 
are nothing else than interfaces that external layers need to implement to interact with the application business logic. 

* **Application (or Transport Layer)**
This is an orchestration intermediate layer. Takes care of adapt outside requests to the domain layer and pass messages
to this one.
Application layer is usually implemented by Commands, CommandBus and EventBus Patterns. 

* **Infrastructure**
In this layer specific *Adapters* for external dependencies are implemented, ad example, APIs or Databases 
implementations.

![Image](https://speakerd.s3.amazonaws.com/presentations/de8629f0bf520131c2e20239d959ba18/slide_11.jpg?1400675141)

## 1.3. Bounded Contexts

To better organize the code into the application and let it to scale properly usually the code inside *src* folder is
grouped into *Bounded Contexts*.

Bounded Contexts represent different approaches to Domain Business logic, for example, different department approaches 
to the same product.

## 1.4. CQRS (Command Query Responsibility Segregation)

To improve hardware scalability and database replication usage CQRS tells us to split our interactions to the Domain 
business logic into *Commands* and *Queries*.

In essence, Commands can change the state of the model (perform write operations) and Queries can NOT (only read 
operations).

This is very convenient if the system database follows a Master-Slave Replication setup because Queries can receive 
slave database connections and commands are the only ones allow to get master database connections.

The current implementation follows this approach.

## 1.5. Application Contexts

Hexagonal Architecture lets write code that is highly decoupled from the application context that will use it.

Possible contexts are:

* A Web Framework like Symfony or Laravel;
* A REST API;
* A set of testing TestCases;  

For each context different Adapters can be implemented so the whole business logic can work without any modification.

# 2. Containerized environment

## 2.1. Docker

To make easy to distribute, install and execute the application contains a set of Docker containers.

* **Apache**
Web server container.
 
* **Php-Fpm 7.1**
Php libraries to work together with the Web framework.
 
* **Php-cli 7.1** 
Php libraries to run Tests and Cli-Scripts. 

* **MySQL Master-Salve Database**
MySQL database server, master-slave replicated to perform high availability and work in a CQRS environment.

* **Composer**
Composer package manager container.
 
## 2.2. Master-Slave MySQL Replication

As already explained, project includes a MySQL database server, master-slave replicated.

Replication helps to perform in a high scalability and high availability environment. 
Commands work with the Master connection (write access) and Queries work with one of the slave connections (in this 
case there is only one slave) with read-only access. 
 
## Doctrine Persistent Adapters

To access the MySQL database cluster *Persistence Adapters* have been implemented for Doctrine.

# 3. Testing

Project includes two types of tests:

* **Unit Tests**
For the Domain Layer or application business logic.
Those are very fast tests not depending on any external implementation.
Thanks to the *Persistence Mock Adapters* implementations these tests use in-memory mocked databases. These are not real 
databases (like SQLite In-memory) but just stubs that implement the Persistence Interfaces.

* **Integration Tests**
The goal for these tests is to perform real *end-to-end* operations.
These tests, basically, create Queries and Commands and send these messages through a CommandBus.
They inject Doctrine implementations for the *Persistence Mock Adapters* and uses *Database Fixtures* to get a 
'testable' initial state. 

# 4. Installation

Finally, how to get Up and Running. Extract the code into a Docker capable computer (I use Linux) and execute these 
commands:

1. Get a copy of the environment params (you don't really need to modify it this time if you don't want, but you should):
```
cp ./docker-compose.override.yml.dist ./docker-compose.override.yml
```

2. Launch the containers:
```
docker-compose up
```

3. Get the dependencies:
```
docker-compose run composer install
```

4. Set permissions properly (still working on how to avoid this line):
```
sudo chown -R $USER: .
```

5. Generate the Database:
```
docker-compose run cli vendor/bin/doctrine orm:schema-tool:create
```

# 5. Usage

1. Run both Unit and Integration tests and generate code coverage (coverage goes into var directory):  
```
docker-compose run cli vendor/bin/phpunit
```

2. Run Symfony commands: 
```
docker-compose run cli php /var/www/application.php post:create
docker-compose run cli php /var/www/application.php post:find [post-id]

```

# 6. Dependencies

Production code dependencies:

* php version 7.1;
* symfony/console;
* league/tactician. CommandBus implementation;
* monolog/monolog. PSR-3 logger;
* league/event. EventBus implementation;
* ramsey/uuid;
* doctrine/orm. Database ORM;
  
Testing dependencies:
  
* phpunit;
* doctrine/data-fixtures;
