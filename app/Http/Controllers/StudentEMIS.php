<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\GenderTranslation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\GenderTranslationStoreRequest;
use App\Http\Requests\GenderTranslationUpdateRequest;

class StudentEMIS extends Controller
{


    public function applicant(Request $request): View
    {
        $search = $request->get('search', '');

        $applicants = DB::table('users')->select('*')->paginate(5);

        return view(
            'app.emis.student.applicant.index',
            compact('applicants', 'search')
        );
    }

    public function overview(Request $request): View
    {
        $search = $request->get('search', '');

        $overviews = DB::table('users')->select('*')->paginate(5);

        return view(
            'app.emis.student.overview.index',
            compact('overviews', 'search')
        );
    }

    public function enrollment(Request $request): View
    {
        $search = $request->get('search', '');

        $enrollments = DB::table('users')->select('*')->paginate(5);

        return view(
            'app.emis.student.enrollment.index',
            compact('enrollments', 'search')
        );
    }

    public function results(Request $request): View
    {
        $search = $request->get('search', '');

        $results = DB::table('users')->select('*')->paginate(5);

        return view(
            'app.emis.student.result.index',
            compact('results', 'search')
        );
    }

    public function graduates(Request $request): View
    {
        $search = $request->get('search', '');

        $graduates = DB::table('users')->select('*')->paginate(5);

        return view(
            'app.emis.student.graduate.index',
            compact('graduates', 'search')
        );
    }

    public function attrition(Request $request): View
    {
        $search = $request->get('search', '');

        $attritions = DB::table('users')->select('*')->paginate(5);

        return view(
            'app.emis.student.attrition.index',
            compact('attritions', 'search')
        );
    }

    public function internship(Request $request): View
    {
        $search = $request->get('search', '');

        $internships = DB::table('users')->select('*')->paginate(5);

        return view(
            'app.emis.student.internship.index',
            compact('internships', 'search')
        );
    }

    public function employment(Request $request): View
    {
        $search = $request->get('search', '');

        $employments = DB::table('users')->select('*')->paginate(5);

        return view(
            'app.emis.student.employment.index',
            compact('employments', 'search')
        );
    }


    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request): View
    // {
    //     $this->authorize('view-any', GenderTranslation::class);

    //     $search = $request->get('search', '');

    //     $genderTranslations = GenderTranslation::search($search)
    //         ->latest()
    //         ->paginate(5)
    //         ->withQueryString();

    //     return view(
    //         'app.gender_translations.index',
    //         compact('genderTranslations', 'search')
    //     );
    // }
}
