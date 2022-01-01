# PHP Application using DDD CQRS Event Sourcing with Hexagonal Architecture

![alt text](documentation/ddd-cqrs-event-sourcing-php-hexagonal-architecture.png "PHP Application using DDD CQRS Event Sourcing Symfony with Hexagonal Architecture") 

Application shows the how complex systems can be built with ease using PHP.      
The example using CQRS DDD and Event Sourcing backed by [Prooph](http://getprooph.org/) in [Symfony](http://symfony.com/) using [Ecotone Framework](https://github.com/ecotoneframework/ecotone).  

Have fun :)

# Run using docker-compose

```php 
cd docker-compose && docker-compose up -d
```
And then application is available under `localhost:3000`  
As read model is updated asynchronously, you may need to refresh after performing action to see the changes.

# Run on local Kubernetes

In order to set up local cluster with ease follow the [instructions from here](https://github.com/dgafka/local-kuberentes-cluster-over-https).
And install [skaffold](https://skaffold.dev/) for automatic code synchronization to your Kubernetes.
You will be able to modify the code and see the changes instantly on same deployment. 

```php
Add to your hosts file (/etc/hosts) ecotone.local.dev for your docker ip address (127.0.0.1 for linux)
Run `skaffold dev --tail`
Enter `https://ecotone.local.dev` and enjoy the application :)  
```

If you want to get more information on how it everything play together visit the [blog post](https://blog.ecotone.tech).

# Possible Integrations with Laravel and Ecotone Lite

Application is written in `Symfony`, however `Ecotone` integrates with `Laravel` and can be run alone without any additional framework (`Ecotone Lite`).  
All the code that is written in here, will work exactly the same way, when will be run with `Laravel` or `Ecotone Lite`.
