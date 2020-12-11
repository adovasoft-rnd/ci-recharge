<?php namespace {namespace}\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Services;

/**
 * Short description of this class usages
 *
 * @class {name}
 * @generated_by CI-Recharge
 * @package {namespace}
 * @implements FilterInterface
 * @created_at {created_at}
 */

class {name} implements FilterInterface
{
    /**
     * @param RequestInterface $request
     * @param null $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        //Validator Instants
        $validator = Services::validation();

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
