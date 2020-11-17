# Implementation Of Filter File

#### Create a Filter file

##### Command
```$xslt
php spark make:filter filter_Name
```
##### Example
```
php spark make:filter TextForm
```
##### Output
```
<?php namespace App\Filters;


use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Services;

/**
 * @class TextFormFilter 
 * @author CI-Recharge
 * @package App
 * @extend Filter.
 * @created 17 November, 2020 05:03:52 AM
 */

class TextFormFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     *
     * @param null $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        //Validator Instants
        $validator = Services::validation();
        
        //Session Instants
        $session = Services::session();
        
    }

    //--------------------------------------------------------------------

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param null $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
```
#### Create a filter file specific namespace
##### Command
```$xslt
php spark make:filter filter_name -n namespace_name
```
##### Example
```
php spark make:filter DocumentForm -n robin
```

##### Output
```
<?php namespace robin\Filters;


use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Services;

/**
 * @class DocumentFormFilter 
 * @author CI-Recharge
 * @package robin
 * @extend Filter.
 * @created 17 November, 2020 05:28:18 AM
 */

class DocumentFormFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     *
     * @param null $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        //Validator Instants
        $validator = Services::validation();
        
        //Session Instants
        $session = Services::session();
        
    }

    //--------------------------------------------------------------------

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param null $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}

```