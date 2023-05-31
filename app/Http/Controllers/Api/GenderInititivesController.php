<?php
namespace App\Http\Controllers\Api;

use App\Models\Gender;
use App\Models\Inititive;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\InititiveCollection;

class GenderInititivesController extends Controller
{
    public function index(Request $request, Gender $gender): InititiveCollection
    {
        $this->authorize('view', $gender);

        $search = $request->get('search', '');

        $inititives = $gender
            ->inititives()
            ->search($search)
            ->latest()
            ->paginate();

        return new InititiveCollection($inititives);
    }

    public function store(
        Request $request,
        Gender $gender,
        Inititive $inititive
    ): Response {
        $this->authorize('update', $gender);

        $gender->inititives()->syncWithoutDetaching([$inititive->id]);

        return response()->noContent();
    }

    public function destroy(
        Request $request,
        Gender $gender,
        Inititive $inititive
    ): Response {
        $this->authorize('update', $gender);

        $gender->inititives()->detach($inititive);

        return response()->noContent();
    }
}
