<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Collection;

/**
 * Enumeration of use case result types mapped to HTTP status codes.
 *
 * @see \TeamMatePro\UseCaseBundle\Http\RestApi\ResultRestRenderer for HTTP status mapping
 */
enum ResultType
{
    // 2xx Success
    case SUCCESS;           // 200 OK - Successful GET, PATCH operations
    case SUCCESS_CREATED;   // 201 Created - Successful POST creating a resource
    case ACCEPTED;          // 202 Accepted - Async operations, background jobs
    case SUCCESS_NO_CONTENT; // 204 No Content - Successful DELETE operations

    // 4xx Client Errors
    case FAILURE;           // 400 Bad Request - Business rule violations, validation errors
    case UNAUTHORIZED;      // 401 Unauthorized - Authentication required
    case FORBIDDEN;         // 403 Forbidden - Authenticated but not authorized
    case NOT_FOUND;         // 404 Not Found - Resource doesn't exist
    case DUPLICATED;        // 409 Conflict - Resource already exists
    case GONE;              // 410 Gone - Resource was deleted
    case EXPIRED;           // 410 Gone - Resource has expired
    case PRECONDITION_FAILED; // 412 Precondition Failed - ETag mismatch, version conflict
    case UNPROCESSABLE;     // 422 Unprocessable Entity - Semantic validation errors
    case LOCKED;            // 423 Locked - Resource locked (e.g., foreign key constraint)
    case TOO_MANY_REQUESTS; // 429 Too Many Requests - Rate limiting

    // 5xx Server Errors
    case SERVICE_UNAVAILABLE; // 503 Service Unavailable - Temporary unavailability
}
