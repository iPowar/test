<?php
namespace Acme\ApiBundle\Exception;

use Exception;

/**
 * @author Mikhail Kudryashov <mikhail.kudryashov@opensoftdev.ru>
 */
class ApiException extends Exception
{
    /**
     * @return ApiException
     */
    public static function notAuthorizedException()
    {
        return new self('not Authorized', 401);
    }

    /**
     * @return ApiException
     */
    public static function AccessDeniedException()
    {
        return new self('Access Denied', 403);
    }

    /**
     * @return ApiException
     */
    public static function NotFoundException()
    {
        return new self('Not Found', 404);
    }
}