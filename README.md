# Codeigniter4 Recharge
CI-recharge is a CLI Tools for skeleton file generation for **CodeIgniter4** PHP Framework. 
It is currently offering only skeleton generation using the **php spark make** commands.
Default Namespace for all files are **App** and location will be detected from autoloader services.
## Features
This are the currently available features @1.0.0
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
**CI-Recharge Dose not create any folder to store files.**
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
|Config|`php spark make:config`|
|Config with Namespace|`php spark make:config -n ns`|
|Controller|`php spark make:controller cont`|
|Controller with Namespace|`php spark make:controller cont -n ns`|
|Controller with Parent|`php spark make:controller cont -b base`|
|Controller with Parent and Namespace|`php spark make:controller cont -n ns -b base`|
|REST Controller|`php spark make:controller cont -rest`|
|REST Controller with Namespace|`php spark make:controller cont -n ns -rest`|
|REST Controller with Parent|`php spark make:controller cont -b base -rest`|
|REST Controller with Parent and Namespace|`php spark make:controller cont -n ns -b base -rest`|
|Entity|`php spark make:entity en`|
|Entiy with Namespace|`php spark make:entity en -n ns`|
|Filter|`php spark make:filter fn`|
|Filter with Namespace|`php spark make:filter fn -n ns`|
|Migration|`php spark make:migrate mg`|
|Migration with Namespace|`php spark make:migrate mg -n ns`|
|Model|`php spark make:model md`|
|Model with Namespace|`php spark make:model md -n ns`|
|Seeder|`php spark make:seed sd`|
|Seeder with Namespace|`php spark make:seed sd -n ns`|

## Future Development
1. Create new migration files from existing database tables
2. Create new seeder files from table data
