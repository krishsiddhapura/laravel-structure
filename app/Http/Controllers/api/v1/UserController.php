<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResponseTrait;

    protected $fetchedUsers = [];

    // List Users Module
    public function listUsers(Request $request)
    {
        // Get the pagination limit from the request, default to 10
        $limit = $request->get('limit', 10);

        // Fetch users excluding already fetched users
        $qry_params = http_build_query($request->except('page'));
        $users = User::whereNotIn('id', $this->fetchedUsers)
            ->paginate($limit)
            ->toArray();

        // Return the response
        return $this->sendListResponse("Users", $users,$qry_params);
    }
}
