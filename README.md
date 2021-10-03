# PHP Application using DDD CQRS Event Sourcing with Hexagonal Architecture

![alt text](documentation/ddd-cqrs-event-sourcing-php-hexagonal-architecture.png "PHP Application using DDD CQRS Event Sourcing with Hexagonal Architecture") 

Application shows the how complex systems can be built with ease using PHP.      
The example using CQRS DDD and Event Sourcing backed by [Prooph](http://getprooph.org/) in [Symfony](http://symfony.com/) using [Ecotone Framework](https://github.com/ecotoneframework/ecotone).  

Have fun :)

# Run on local Kubernetes Cluster

As you can see `kubernetes` catalog, there are only k8s manifest. There is no DockerFile. 
This comes from the fact, that is sample project is using [paketo buildpacks](https://paketo.io). 
Locally we are deploying to the cluster using [skaffold](https://skaffold.dev/). 
If you want to get more information on how it everything play together visit the [blog post](https://blog.ecotone.tech).

# Run using docker-compose

```php 
cd docker-compose && docker-compose up -d
```
And then application is available under `localhost:3000`  
As read model is updated asynchronously, you may need to refresh after performing action to see the changes.

# Possible Integrations with Laravel and Ecotone Lite

Application is written in `Symfony`, however `Ecotone` integrates with `Laravel` and can be run alone without any additional framework (`Ecotone Lite`).  
All the code that is written in here, will work exactly the same way, when will be run with `Laravel` or `Ecotone Lite`.
