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

        $applicants = DB::connection('mysql_srs')
        ->table('student as s')
        ->join('sf_guard_user as sf', 'sf.id', '=', 's.sf_guard_user_id')
        // ->join('student_info as ifo', 's.id', '=', 'ifo.student_id')
        ->join('student_detail as sd', 's.id', '=', 'sd.student_id')
        // ->join('country as c', 'sd.country_id', '=', 'c.id')
        ->join('state as st', 'sd.state_id', '=', 'st.id')
        ->join('zone as z', 'sd.zone_id', '=', 'z.id')
        ->join('woreda as w', 'sd.woreda_id', '=', 'w.id')
        ->join('program as p', 's.program_id', '=', 'p.id')
        ->join('program_level as pl', 'p.program_level_id', '=', 'pl.id')
        ->join('department as d', 'd.id', '=', 'p.department_id')
        ->select(
            's.student_id',
            // 'ifo.academic_year',
            // 'ifo.year',
            DB::raw('(SELECT MAX(academic_year) FROM student_info WHERE student_id = s.id) AS academic_year'),
            's.id',
            'sf.username',
            'sf.first_name',
            'sf.fathers_name',
            'sf.grand_fathers_name',
            's.birth_date',
            's.sex',
            'sd.telephone',
            'sd.kebele',
            'sd.place_of_birth',
            'sd.mother_name',
            'sd.family_phone',
            // 'c.code AS country_code',
            'st.region_code AS state_code',
            'z.zone_code AS zone_code',
            'w.woreda_code AS woreda_code',
            'd.code AS department_code',
            'p.code AS program_code',
            'pl.code AS program_level_code'
        )
        ->orderBy('s.student_id', 'desc')
        ->paginate(10);

        // dd($applicants);

        return view(
            'app.emis.student.applicant.index',
            compact('applicants', 'search')
        );
    }

    public function overview(Request $request): View
    {
        $search = $request->get('search', '');

        $overviews = DB::connection('mysql_srs')
        ->table('student as s')
        ->join('sf_guard_user as sf', 'sf.id', '=', 's.sf_guard_user_id')
        ->join('student_detail as sd', 's.id', '=', 'sd.student_id')
        // ->join('country as c', 'sd.country_id', '=', 'c.id')
        ->join('state as st', 'sd.state_id', '=', 'st.id')
        ->join('zone as z', 'sd.zone_id', '=', 'z.id')
        ->join('woreda as w', 'sd.woreda_id', '=', 'w.id')
        ->join('program as p', 's.program_id', '=', 'p.id')
        ->join('program_level as pl', 'p.program_level_id', '=', 'pl.id')
        ->join('department as d', 'd.id', '=', 'p.department_id')
        ->select(
            's.student_id',
            's.id',
            'sf.first_name',
            'sf.fathers_name',
            'sf.grand_fathers_name',
            's.birth_date',
            's.sex',
            'sd.telephone',
            'sd.kebele',
            'sd.place_of_birth',
            'sd.entrance_exam_id',
            // 'sd.mother_name',
            // 'sd.family_phone',
            // 'c.code AS country_code',
            'st.region_code AS state_code',
            'z.zone_code AS zone_code',
            'w.woreda_code AS woreda_code',
            'd.code AS department_code',
            'p.code AS program_code',
            'pl.code AS program_level_code'
        )
        ->orderBy('s.student_id', 'desc')
        ->paginate(10);

        return view(
            'app.emis.student.overview.index',
            compact('overviews', 'search')
        );
    }

    public function enrollment(Request $request): View
    {
        $search = $request->get('search', '');

        $enrollments = DB::connection('mysql_srs')
        ->table('student as s')
        ->join('sf_guard_user as sf', 'sf.id', '=', 's.sf_guard_user_id')
        ->join('student_info as ifo', 's.id', '=', 'ifo.student_id')
        ->join('student_detail as sd', 's.id', '=', 'sd.student_id')
        // ->join('country as c', 'sd.country_id', '=', 'c.id')
        // ->join('disability as d', 'sd.disability_id', '=', 'd.id')
        ->join('program as p', 's.program_id', '=', 'p.id')
        ->join('program_level as pl', 'p.program_level_id', '=', 'pl.id')
        ->join('enrollment_type as et', 'p.enrollment_type_id', '=', 'et.id')
        ->join('department as d', 'd.id', '=', 'p.department_id')
        // ->join('college as col', 'd.college_id', '=', 'col.id')
        // ->join('campus as ca', 'col.campus_id', '=', 'ca.id') // if the structure is thought like this
        ->select(
            's.student_id',
            'ifo.academic_year',
            'ifo.semester AS academic_period', // later check where each academic period data code is stored, for now just the value

            // Not sure which columns match the excel colummns for gpa and ECTS based data, figure out later
            'ifo.semester_ects AS current_registered_credits',
            'ifo.total_ects AS cumulative_registered_credits',
            // 'round((ifo.total_grade_points / ifo.total_ects),2) AS cumulative_gpa',
            // 'round((si.semester_grade_points / si.semester_ects) ,2) as sgpa,',
            DB::raw('ROUND(ifo.total_grade_points / ifo.total_ects, 2) as cumulative_gpa'),

            'ifo.year AS year_level',
            // 'c.code AS country_code',
            // 'd.code AS student_disability',
            // 'ca.code AS campus_code',
            // 'col.code AS college_code',
            'd.code AS department_code',
            'p.code AS program_code',
            'pl.code AS target_qualification',
            'et.enrollment_type_name AS program_modality' // later change to et.code when code column is added in the table
        )
        ->orderBy('s.student_id', 'desc')
        ->paginate(10);

        return view(
            'app.emis.student.enrollment.index',
            compact('enrollments', 'search')
        );
    }

    public function results(Request $request): View
    {
        $search = $request->get('search', '');

        $results = DB::connection('mysql_srs')
        ->table('student as s')
        ->join('sf_guard_user as sf', 'sf.id', '=', 's.sf_guard_user_id')
        ->join('student_info as ifo', 's.id', '=', 'ifo.student_id')
        ->join('student_status as ss', 'ifo.status_id', '=', 'ss.id')
        ->select(
            's.student_id',
            'ifo.academic_year',
            'ifo.semester AS academic_period', // later check where each academic period data code is stored, for now just the value
            'ss.status_name AS result', // change later to ss.code if code column added on student_status table

            // Not sure which columns match the excel columns for gpa and ECTS based data, figure out later
            'ifo.total_ects AS total_accumulated_credits',
            DB::raw('ROUND(ifo.semester_grade_points / ifo.semester_ects ,2) as gpa'),
            DB::raw('ROUND(ifo.total_grade_points / ifo.total_ects, 2) as cgpa'),

            // I think this is all the semester count taken in that year, not sure yet
            // DB::raw('count(ifo.semester) as total_academic_periods'),
        )
        ->orderBy('s.student_id', 'desc')
        ->paginate(10);

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
