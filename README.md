# wild-series
06 - Symfony : Créer ta première entité avec Doctrine

# Installation
* Just clone this repository, if it is already done, launch a terminal inside the root folder and run `git checkout first-entity`
* Next, do a `composer install`
* And you can run the symphony server with `symfony server:start`

# Verify the entities
To check the entities you first need to modify the file `.env.local` to configure your database connection. Once done, launch a terminal in the root folder of the project and run the following commands :
* `php bin/console make:migration` to create the migration file
* `php bin/console doctrine:migrations:migrate` to launch the migration
After that, launch these command to verify if the entities are correctly linked to your database :
* `php bin/console doctrine:mapping:info`, witch should print :
 ```php bin/console log
 Found 2 mapped entities:

 [OK]   App\Entity\Category
 [OK]   App\Entity\Program
```


* `php bin/console doctrine:schema:validate` witch should print :
```php bin/console log
Mapping
-------

                                                                                                                        
 [OK] The mapping files are correct.                                                                                    
                                                                                                                        

Database
--------

                                                                                                                        
 [OK] The database schema is in sync with the mapping files.                                                            
                                                                                                                        

```
