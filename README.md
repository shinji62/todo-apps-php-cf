## About
A backend for the ToDo app written using PHP.  
ToDos can be stored in Mongo DB. 
#
## Prerequisites
[CloudFoundry Command Line](https://github.com/cloudfoundry/cli) installed.

## Deploying To CF

Login to CF ```bash cf login -a yourendpoint``
Push to CF ```bash cf push todo --no-start```
Create MongoDB service ```bash cf create-service mongolab sandbox todo-mongo-db```depends onyour mongodb conf.
Bind MongoDB service ```bash  cf bind-service ge-todo todo-mongo-db```
Start Application ```bash cf start todo``` 


## Additional Information

This app currently uses the PHP buildpack from [CloudFoundry](https://github.com/cloudfoundry/php-buildpack).

By default it is run unsing an Apache server however you can also use NGINX. 
To do this open the  options.json file in the .bp-config directory and change the WEB_SERVER property from httpd to nginx.


## Authors
Gwenn Etourneau Pivotal 2015

