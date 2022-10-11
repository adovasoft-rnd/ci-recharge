# Codeigniter4 Recharge
[![Build Status](https://scrutinizer-ci.com/g/hafijul233/ci-recharge/badges/build.png?b=master)](https://scrutinizer-ci.com/g/hafijul233/ci-recharge/build-status/master)
[![Latest Stable Version](https://poser.pugx.org/hafijul233/ci-recharge/v)](//packagist.org/packages/hafijul233/ci-recharge) 
[![Total Downloads](https://poser.pugx.org/hafijul233/ci-recharge/downloads)](//packagist.org/packages/hafijul233/ci-recharge) 
[![Latest Unstable Version](https://poser.pugx.org/hafijul233/ci-recharge/v/unstable)](//packagist.org/packages/hafijul233/ci-recharge) 
[![License](https://poser.pugx.org/hafijul233/ci-recharge/license)](//packagist.org/packages/hafijul233/ci-recharge)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/hafijul233/ci-recharge/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/hafijul233/ci-recharge/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/hafijul233/ci-recharge/?branch=master)


CI-recharge is a CLI Tools for skeleton file generation for **CodeIgniter4** PHP Framework. 
It is currently offering only skeleton generation using the **php spark make** commands.
Default Namespace for all files are **App** and location will be detected from autoloader services.
## Features
Currently available features
- Configuration File 
- Configuration File with Namespace
- Basic Controller File
- Basic Controller with Namespace
- Basic Controller with Specific Parent Class
- REST Controller File
- REST Controller with Namespace
- REST Controller with Specific Parent Class
- Entity File 
- Entity File with Namespace
- Filter File
- Filter File with Namespace
- Migration File
- Migration File with Namespace
- Model File
- Model File with Namespace
- Seeder File 
- Seeder File with Namespace
### Notices
**CI-Recharge Dose not create any folder to store file.**
**For Entity a folder named `Entities` must be created under namespace**
Example: 
+ app
    + Config
    + Controllers
    + `Entities`
    + Models
    + Views

### Command Syntax
Detail Implementation of every command are given in `src\Example\` Folder.
- **`ns` represents Namespace name**
- **`conf` represents Configuration name**
- **`cont` represents Controller name**
- **`rest` represents Rest Controller name**
- **`base` represents Base Controller name**
- **`en` represents Entity name**
- **`fn` represents Filter name**
- **`mg` represents Migration name**
- **`md` represents Model name**
- **`sd` represents Seeder name**

Basic Syntax are given below for **make series**:

|Command|syntax|
|-------|------|
|Config|`php spark create:config`|
|Config with Namespace|`php spark create:config -n ns`|
|Controller|`php spark create:controller cont`|
|Controller with Namespace|`php spark create:controller cont -n ns`|
|Controller with Parent|`php spark create:controller cont -b base`|
|Controller with Parent and Namespace|`php spark create:controller cont -n ns -b base`|
|REST Controller|`php spark create:controller cont -rest`|
|REST Controller with Namespace|`php spark create:controller cont -n ns -rest`|
|REST Controller with Parent|`php spark create:controller cont -b base -rest`|
|REST Controller with Parent and Namespace|`php spark create:controller cont -n ns -b base -rest`|
|Entity|`php spark create:entity en`|
|Entiy with Namespace|`php spark create:entity en -n ns`|
|Filter|`php spark create:filter fn`|
|Filter with Namespace|`php spark create:filter fn -n ns`|
|Migration|`php spark create:migrate mg`|
|Migration with Namespace|`php spark create:migrate mg -n ns`|
|Model|`php spark create:model md`|
|Model with Namespace|`php spark create:model md -n ns`|
|Seeder|`php spark create:seed sd`|
|Seeder with Namespace|`php spark create:seed sd -n ns`|

## Future Development
1. Create new migration files from existing database tables
2. Create new seeder files from table data
