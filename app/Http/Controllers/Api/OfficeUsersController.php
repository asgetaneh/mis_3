<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;

class OfficeUsersController extends Controller
{
    public function index(Request $request, Office $office): UserCollection
    {
        $this->authorize('view', $office);

        $search = $request->get('search', '');

        $users = $office
            ->users()
            ->search($search)
            ->latest()
            ->paginate();

        return new UserCollection($users);
    }

    public function store(
        Request $request,
        Office $office,
        User $user
    ): Response {
        $this->authorize('update', $office);

        $office->users()->syncWithoutDetaching([$user->id]);

        return response()->noContent();
    }

    public function destroy(
        Request $request,
        Office $office,
        User $user
    ): Response {
        $this->authorize('update', $office);

        $office->users()->detach($user);

        return response()->noContent();
    }
}
