# Resilient Message-Driven Systems with Ecotone Framework

# Objectives

- To provide a simple example of how to implement `Domain Driven Design` and `Messaging Architecture` in PHP.  
- To show how to implement `CQRS`, `Event Sourcing` and `Hexagonal Architecture` in PHP.
- To show how to implement `Resilient` and `Observable` `Microservices` in PHP.

# About
  
It does not really matter if we work in `E-Commerce`, `ERP` or `Finance based systems`, we can be sure that system will be resilient, observable and maintainable in long term.  
And we can achieve using principles of `Message-Driven Systems` combined with `Domain Driven Design` using patterns like `CQRS`, `Event Sourcing` and `Service Bus` in PHP.  
And to make it happen we will use `Ecotone Framework` combined with `Symfony` or `Laravel`.  

# What is Ecotone

[Ecotone is Message-Driven Framework](https://medium.com/dev-genius/building-reactive-message-driven-systems-in-php-ba185697becf) for building resilient, business oriented systems in PHP.
Ecotone allows to build `Microservices` and `Event-Driven` systems in PHP with ease.  
Thanks to foundation build around Message Driven Architecture, it allows to build `Resilient` and `Observable` systems in PHP.  
On top of that it provides patterns from DDD like - `Aggregates`, `Domain Events`, `Projections`, `Sagas`, and more.
Ecotone integrates with `Symfony` and `Laravel` and allows you to connect Services seamlessly using `Microservice` or `Event-Driven` architecture.

In short Ecotone is a powerhouse to build reliable systems in PHP in a way that is maintainable, scalable and most importantly resilient.

# Playing with the demo

Run `docker-compose up -d` to start all services.

Go to [Customer Service](http://localhost:3000/) to report issue as a customer.   
Whenever issue is reported, confirming email will be sent to the customer, which can be found [Mailbox](http://localhost:3004/).   
First time email is sent it's set up for failing, so you can get feeling of working with system that can recover from error messages using [Ecotone Pulse](http://localhost:3006/service/customer_service).  
From [Backoffice Service](http://localhost:3001/prepared-tickets) you may start working on ticket, that was correlated with issue reported by customer.  
You may also check [Jaeger](http://localhost:3007/) to get full overview of how communication looks like and how each component behave and what is happening.

# Stack

- [Ecotone](https://docs.ecotone.tech/) - PHP Framework for building resilient, business oriented system in PHP
- [Symfony](https://symfony.com/) - PHP Framework for building web applications
- [Laravel](https://laravel.com/) - PHP Framework for building web applications
- [RabbitMQ](https://www.rabbitmq.com/) - Message Broker for asynchronous communication
- [Ecotone Pulse](https://docs.ecotone.tech/modules/ecotone-pulse) - Monitor for any processing errors and recover from failed messages.
- [OpenTelemetry with Jaeger](https://www.jaegertracing.io/) - Distributed Tracing for monitoring and troubleshooting microservices-based distributed systems

# Business Domain

## Customer Service

- `Customer Service` is `Laravel+Ecotone application`, where customers can report issues. This happens using `CQRS` combined with `Eloquent Models`.  

![Laravel CQRS](documentation/customer-service.png "Laravel CQRS") 

------

- Whenever new issue is reported, email is send to the with confirmation. This happens using `Asynchronous Event Handlers`, which are backed by `RabbitMQ`.

![Laravel asynchronous event handling](documentation/issue-reported.png "Laravel asynchronous events")

## Backoffice Service

- `Backoffice Service` is `Symfony+Ecotone application`, where employees can be registered and work on the tickets.  
All issues reported by customers are synchronized to `Backoffice Service` and are correlated with tickets.  
The synchronization between `Customer Service` and `Backoffice Service` is done using `Events` and `Distributed Bus` for cross service communication via `RabbitMQ`.  

![Symfony Microservice](documentation/ddd-cqrs-event-sourcing-php-hexagonal-architecture.png "Symfony Microservice")  

------

- Employees can work on the tickets provides information about the status. As we want to know full history, Tickets are `Event-Sourced`.    
From Event Sourced tickets we build different `Read Model Projections`, which are used to display information about the ticket.  

## Tracing and Monitoring

- The whole communication between Services is monitored and traced using [OpenTelemetry](https://opentelemetry.io/) with `Jaeger`.    
![OpenTelemetry Jaeger](documentation/tracing_jaeger_php.png "OpenTelemetry Tracing in PHP with Jaeger")  

------

- Any issue that happens in the system is reported to `Ecotone Pulse`, which is used to recover from failed messages.  
![Ecotone Pulse](documentation/ecotone_pulse.png "Ecotone Pulse")