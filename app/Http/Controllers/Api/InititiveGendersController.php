<?php
namespace App\Http\Controllers\Api;

use App\Models\Gender;
use App\Models\Inititive;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenderCollection;

class InititiveGendersController extends Controller
{
    public function index(
        Request $request,
        Inititive $inititive
    ): GenderCollection {
        $this->authorize('view', $inititive);

        $search = $request->get('search', '');

        $genders = $inititive
            ->genders()
            ->search($search)
            ->latest()
            ->paginate();

        return new GenderCollection($genders);
    }

    public function store(
        Request $request,
        Inititive $inititive,
        Gender $gender
    ): Response {
        $this->authorize('update', $inititive);

        $inititive->genders()->syncWithoutDetaching([$gender->id]);

        return response()->noContent();
    }

    public function destroy(
        Request $request,
        Inititive $inititive,
        Gender $gender
    ): Response {
        $this->authorize('update', $inititive);

        $inititive->genders()->detach($gender);

        return response()->noContent();
    }
}
