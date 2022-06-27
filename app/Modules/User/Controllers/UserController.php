<?php

namespace App\Modules\User\Controllers;

use App\Modules\Api\Utilities\ApiFilter;
use App\Modules\Api\Utilities\ApiWith;
use App\Modules\Api\Utilities\Pagination;
use App\Modules\Api\Validator\Validator;
use App\Modules\OpenApi\Utilities\OpenApiResponse;
use App\Modules\User\Conditions\HasEmail;
use App\Modules\User\Conditions\HasPassword;
use App\Modules\User\Conditions\UserDoesExist;
use App\Modules\User\Conditions\UserDoesNotExist;
use App\Modules\User\Dto\UserDto;
use App\Modules\User\Services\UserService;
use App\Modules\User\Transformers\UserTransformer;
use App\Modules\User\With\UserWith;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController
{
    public function __construct(
        private UserTransformer $transformer,
        private UserService $service
    ) {}

    public function list(Request $request): JsonResponse
    {
        $pagination = Pagination::fromRequest($request);

        $apiFilter = ApiFilter::fromRequest($request);

        $userWith = UserWith::fromRequest($request);

        $users = $this->service->list($pagination, $apiFilter);

        $result = $this->transformer->transformCollection($users, $pagination, $userWith);

        return OpenApiResponse::success($request, $result);
    }
}
