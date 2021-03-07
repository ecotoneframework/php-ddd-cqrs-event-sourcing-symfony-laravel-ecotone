# PHP Application using DDD CQRS Event Sourcing with Hexagonal Architecture

![alt text](ddd-cqrs-event-sourcing-php-hexagonal-architecture.png "PHP Application using DDD CQRS Event Sourcing with Hexagonal Architecture")

Application built with [Ecotone Framework](https://docs.ecotone.tech) and powered by integrations 
with [Prooph Event Store](http://getprooph.org/), [Symfony](http://symfony.com/) and [JMS Serializer](https://github.com/schmittjoh/serializer).  

Application shows the how complex systems can be built with ease using PHP.      
The main tenets of Ecotone is to allow developers focus on business problems not integrations and technical issues
and this can be seen in the code.    

Have fun :)

# Possible Integrations with Laravel and Ecotone Lite

Application is written in `Symfony`, however `Ecotone` integrates with `Laravel` and can be run alone without any additional framework (`Ecotone Lite`).  
All the code that is written in here, will work exactly the same way, when will be run with `Laravel` or `Ecotone Lite`.

# Run

```php 
docker-compose up -d
```
And then application is available under `localhost:3000`  

As read model is updated asynchronously, you may need to refresh after performing action to see the changes.