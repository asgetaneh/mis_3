<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfficeCollection;

class UserOfficesController extends Controller
{
    public function index(Request $request, User $user): OfficeCollection
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $offices = $user
            ->offices2()
            ->search($search)
            ->latest()
            ->paginate();

        return new OfficeCollection($offices);
    }

    public function store(
        Request $request,
        User $user,
        Office $office
    ): Response {
        $this->authorize('update', $user);

        $user->offices2()->syncWithoutDetaching([$office->id]);

        return response()->noContent();
    }

    public function destroy(
        Request $request,
        User $user,
        Office $office
    ): Response {
        $this->authorize('update', $user);

        $user->offices2()->detach($office);

        return response()->noContent();
    }
}
