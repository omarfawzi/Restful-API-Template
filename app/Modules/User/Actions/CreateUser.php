<?php

namespace App\Modules\User\Actions;

use App\Modules\Api\Handlers\RequestHandler;
use App\Modules\OpenApi\Utilities\OpenApiResponse;
use App\Modules\User\Conditions\HasEmail;
use App\Modules\User\Conditions\HasPassword;
use App\Modules\User\Conditions\UserDoesNotExist;
use App\Modules\User\Dto\UserDto;
use App\Modules\User\Services\UserService;
use App\Modules\User\Transformers\UserTransformer;
use App\Modules\User\With\UserWith;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateUser extends RequestHandler
{
    public function __construct(
        private UserTransformer $transformer,
        private UserService $service
    ) {}

    public function getValidators(Request $request): array
    {
        return [
            new HasPassword(),
            new HasEmail(),
            new UserDoesNotExist()
        ];
    }

    public function processRequest(Request $request): JsonResponse
    {
        $userDto = UserDto::fromRequest($request);

        $user = $this->service->create($userDto);

        $result = $this->transformer->transform($user, UserWith::createWithDefault());

        return OpenApiResponse::created($request, $result);
    }
}