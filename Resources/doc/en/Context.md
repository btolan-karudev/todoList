#Context

You have just joined a startup whose core business is an application for managing its daily tasks. The company has just started up, and the application had to be developed at full speed to allow to show potential investors that the concept is viable (we speak of Minimum Viable Product or MVP).

The choice of the previous developer was to use the PHP Symfony framework, a framework that you are starting to know well!

Good news ! ToDo & Co has finally managed to raise funds to enable the development of the company and especially the application.  

Your role here is therefore to improve the quality of the application. Quality is a concept that encompasses a number of subjects: we often talk about code quality, but there is also the quality perceived by the user of the application or the quality perceived by the company's employees, and finally the quality that you perceive when you need to work on the project.  

So, for this last specialization project, you are in the shoes of an experienced developer in charge of the following tasks:

    l’implémentation de nouvelles fonctionnalités ;
    la correction de quelques anomalies ;
    et l’implémentation de tests automatisés.  
    
You are also asked to analyze the project using tools allowing you to have an overview of the quality of the code and the various performance axes of the application.  

You are not asked to correct the points raised by the code quality and performance audit. That said, if time permits, ToDo & Co will be happy to reduce the technical debt for this application.

#Description of the requirement

##Bug fixes

###A task must be attached to a user

Currently, when a task is created, it is not linked to a user. You are asked to make the necessary corrections so that automatically, when saving the task, the authenticated user is attached to the newly created task.

When editing the task, the author cannot be changed.

For tasks already created, they must be linked to an "anonymous" user.  

###Choose a role for a user  

When creating a user, it should be possible to choose a role for that user. The roles listed are:

    user role (ROLE_USER);
    administrator role (ROLE_ADMIN).

When modifying a user, it is also possible to change the role of a user.  

## Implementation of new functionalities

###Authorization

Only users with the administrator role (ROLE_ADMIN) should be able to access the user management pages.

Tasks can only be deleted by users who created the tasks in question.

Tasks associated with the “anonymous” user can only be deleted by users with the administrator role (ROLE_ADMIN).  

### Implementation of automated tests

You are asked to implement the automated tests (unit and functional tests) necessary to ensure that the functioning of the application is well suited to the requests.

These tests must be implemented with PHPUnit; you can also use Behat for the functional part.

You will provide test data in order to be able to prove the operation in the cases explained in this document.

You are asked to provide a code coverage report at the end of the project. The coverage rate must be greater than 70%.

##Technical documentation

You are asked to produce documentation explaining how the implementation of authentication was done. This documentation is intended for the next junior developers who will join the team in a few weeks. In this documentation, it should be possible for a beginner with the Symfony framework to:

    understand which file (s) to modify and why;
    how does authentication work;
    and where the users are stored.

If you think it's important to mention other information, don't hesitate to do so.

Furthermore, you are leading the way in terms of collaboration with others on this project. You are also asked to produce a document explaining how all developers wishing to make changes to the project should proceed.

This document should also detail the quality process to be used and the rules to be observed.

## Code quality audit & application performance

The founders want to perpetuate the development of the application. That said, they first want to make an inventory of the technical debt of the application.

At the end of your work on the application, you are asked to produce a code audit on the following two axes: code quality and performance.

Obviously, you are strongly advised to use tools that allow you to have metrics to support your comments.

Regarding performance auditing, the use of Blackfire is mandatory. The latter will allow you to produce precise analyzes adapted to future developments in the project.