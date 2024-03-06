<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\NationInstitutionId;

class StudentEMIS extends Controller
{


    public function applicant(Request $request): View
    {
        $search = $request->get('search', '');
        $applicants = DB::connection('mysql_srs')
        ->table('student as s')
        ->join('sf_guard_user as sf', 'sf.id', '=', 's.sf_guard_user_id')
        ->join('student_info as ifo', 's.id', '=', 'ifo.student_id')
        ->join('student_detail as sd', 's.id', '=', 'sd.student_id')
        ->join('country as c', 'sd.country_id', '=', 'c.id')
        ->join('state as st', 'sd.state_id', '=', 'st.id')
        ->join('zone as z', 'sd.zone_id', '=', 'z.id')
        ->join('woreda as w', 'sd.woreda_id', '=', 'w.id')
        ->join('program as p', 'ifo.program_id', '=', 'p.id')
        ->join('program_level as pl', 'p.program_level_id', '=', 'pl.id')
        ->join('department as d', 'd.id', '=', 'p.department_id')
        ->join('college as col', 'col.id', '=', 'd.college_id')
        ->leftjoin('disabled_students as ds', 's.student_id', '=', 'ds.disabled_student_id')
        ->leftjoin('disability as di', 'ds.disability_id', '=', 'di.id')
        ->select(
            's.student_id',
            // 'ifo.academic_year',
            // 'ifo.year',
            DB::raw('(SELECT MAX(academic_year) FROM student_info WHERE student_id = s.id) AS academic_year'),
            's.id',
            // 'sf.username',
            'sf.first_name',
            'sf.fathers_name',
            'sf.grand_fathers_name',
            's.birth_date',
            's.sex',
            'sd.telephone',
            'sd.kebele',
            'sd.place_of_birth',
            'sd.alternative_email as email',
            'sd.family_phone',
            'sd.entrance_total_mark as national_exam_score',
            'c.country_code',
            'st.region_code AS state_code',
            'z.zone_code',
            'w.woreda_code',
            'd.department_code',
            'p.program_code',
            'pl.code AS program_level_code',
            'di.disability_code as student_disability',
            'col.id as college_id'        )
        ->where([
            'ifo.year' => 1,
            'ifo.semester' => 1,
            'ifo.record_status' => 1
             ])
        -> where('d.department_name', 'not like', '%remedial%')
        ->orderBy('s.student_id', 'desc')->get();

        // dd($applicants);

        return view(
            'app.emis.student.applicant.index',
            compact('applicants', 'search')
        );
    }

    public function overview(Request $request): View
    {
        $search = $request->get('search', '');
        $nation_institute_id = new NationInstitutionId;
        $overviews = DB::connection('mysql_srs')
        ->table('student as s')
        ->join('sf_guard_user as sf', 'sf.id', '=', 's.sf_guard_user_id')
        ->join('student_info as ifo', 's.id', '=', 'ifo.student_id')
        ->join('student_detail as sd', 's.id', '=', 'sd.student_id')
        ->join('country as c', 'sd.country_id', '=', 'c.id')
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
            'sd.alternative_email as email',
            // 'sd.family_phone',
            'c.country_code AS country_code',
            'st.region_code AS state_code',
            'z.zone_code AS zone_code',
            'w.woreda_code AS woreda_code',
            'd.department_code',
            'p.program_code',
            'pl.code AS program_level_code'
        )
        ->where([
            'ifo.record_status' => 1 // only active students for this semester
            ])
        ->orderBy('s.student_id', 'desc')->get();
        //dd($overviews);

        return view(
            'app.emis.student.overview.index',
            compact('overviews', 'nation_institute_id','search')
        );
    }

    public function enrollment(Request $request): View
    {
        $search = $request->get('search', '');
        $nation_institute_id = new NationInstitutionId;
        // $enroll  = DB::connection('mysql_srs')
        // ->table('student_info as ifo')
        // ->select(
        //     'ifo.student_id as stu_info_stu_id',
        //     'ifo.id as stu_info_id'
        // )
        // ->where([
        //     'ifo.record_status' => 1 // only active students for this semester
        // ])
        //  // exclude year 1 and semester 1 students
        // ->where(function($query) {
        //     $query->where(function($subquery) {
        //         $subquery->where('ifo.year', '<>', 1)
        //                  ->where('ifo.semester', '<>', 1);
        //     })->orWhere(function($subquery) {
        //         $subquery->where('ifo.year', '=', 1)
        //                  ->where('ifo.semester', '<>', 1);
        //     })->orWhere(function($subquery) {
        //         $subquery->where('ifo.year', '<>', 1)
        //                  ->where('ifo.semester', '=', 1);
        //     });
        // })
        // ->get() ;


        // $enrollments = getAcademicRecords($enroll);

        $enrollments = DB::connection('mysql_srs')->select('

            SELECT
                s.student_id AS student_id_number,
                sp.sponsor_code,
                ifo.student_id AS stu_info_stu_id,
                ifo.id AS stu_info_id,
                ifo.academic_year,
                astu.action_code AS enrollment_type,
                astu.id AS exchange_type,
                ifo.semester AS academic_period,
                ifo.total_ects AS cumulative_registered_credits,
                ifo.semester_ects AS current_registered_credits,
                ifo.previous_total_ects AS cumulative_completed_credits,
                cl.required_credit AS required_credits,
                cl.number_of_semesters AS required_academic_periods,
                ROUND(ifo.total_grade_points / ifo.total_ects, 2) AS cumulative_gpa,
                ifo.year AS year_level,
                ca.campus_name AS campus_code,
                d.department_code,
                p.program_code,
                pl.code AS target_qualification,
                et.enrollment_type_code AS program_modality,
                di.disability_code AS student_disability,
                fp.foreign_program_code AS foreign_program
            FROM
                student_info ifo
            JOIN student s ON ifo.student_id = s.id
            JOIN sf_guard_user sf ON s.sf_guard_user_id = sf.id
            LEFT JOIN action_on_student astu ON ifo.action = astu.id
            JOIN check_list cl ON ifo.check_list_id = cl.id
            JOIN student_detail sd ON s.id = sd.student_id
            LEFT JOIN campus ca ON sd.campus_id = ca.id
            LEFT JOIN sponsor sp ON sd.sponsor_id = sp.id
            LEFT JOIN disabled_students ds ON s.student_id = ds.disabled_student_id
            LEFT JOIN disability di ON ds.disability_id = di.id
            LEFT JOIN foreign_program fp ON sd.foreign_program_id = fp.id
            JOIN program p ON ifo.program_id = p.id
            JOIN program_level pl ON p.program_level_id = pl.id
            JOIN enrollment_type et ON p.enrollment_type_id = et.id
            JOIN department d ON d.id = p.department_id
            WHERE
                ifo.id = (
                    SELECT MAX(id)
                    FROM student_info s
                    WHERE ifo.student_id = s.student_id
                     AND s.id < (
                        SELECT MAX(id)
                        FROM student_info
                        WHERE student_id = ifo.student_id
                        AND record_status = 1
                    )
                ) and (
                    (
                        ifo.year <> 1 AND ifo.semester <> 1
                    ) OR(
                        ifo.year = 1 AND ifo.semester <> 1
                    ) OR(
                        ifo.year <> 1 AND ifo.semester = 1
                    )
                );

        ');
        // dd($enrollments);
        return view(
            'app.emis.student.enrollment.index',
            compact('enrollments', 'nation_institute_id', 'search')
        );
    }

    public function results(Request $request): View
    {
        $search = $request->get('search', '');
        $nation_institute_id = new NationInstitutionId;
        $query_for_result  = DB::connection('mysql_srs')
        ->table('student_info as ifo')
        ->select(
            'ifo.student_id as stu_info_stu_id',
            'ifo.id as stu_info_id'
        )
        ->where([
            'ifo.record_status' => 1 // only active students for this semester
        ])
        // exclude year 1 and semester 1 students
        ->where(function($query) {
            $query->where(function($subquery) {
                $subquery->where('ifo.year', '<>', 1)
                         ->where('ifo.semester', '<>', 1);
            })->orWhere(function($subquery) {
                $subquery->where('ifo.year', '=', 1)
                         ->where('ifo.semester', '<>', 1);
            })->orWhere(function($subquery) {
                $subquery->where('ifo.year', '<>', 1)
                         ->where('ifo.semester', '=', 1);
            });
        })
        ->get() ;
        //dd($query_for_result);
        $results = getAcademicRecordsOfStudentResult($query_for_result);
        return view(
            'app.emis.student.result.index',
            compact('results', 'nation_institute_id', 'search')
        );
    }

    public function graduates(Request $request): View
    {
        $search = $request->get('search', '');
        $nation_institute_id = new NationInstitutionId;
        $graduates = DB::connection('mysql_srs')
        ->table('student as s')
        ->join('student_info as ifo', 's.id', '=', 'ifo.student_id')
        ->join('program as p', 'ifo.program_id', '=', 'p.id')
        ->join('department as d', 'd.id', '=', 'p.department_id')
        ->join('graduation_list as gl', 'ifo.id', '=', 'gl.student_info_id')
        ->select(
            's.student_id as stud_id',
            'gl.certified_on as graduation_date',
            'gl.cgpa as cgpa',
            'gl.tcrd as total_accumulated_credits',
            'gl.academic_year as academic_year',
            'gl.exit_score as exit_exam_score',
            'd.department_code as institution_code',
            'ifo.academic_year',
            'ifo.semester AS academic_period', // later check where each academic period data code is stored, for now just the value

            // DB::raw('COUNT(ifo.id) as total_academic_periods'),
            DB::raw('(SELECT COUNT(*) FROM student_info si WHERE si.student_id = s.id) as total_academic_periods')

            // Not sure which columns match the excel columns for gpa and ECTS based data, figure out later
            // 'ifo.total_ects AS total_accumulated_credits',
            // DB::raw('ROUND(ifo.total_grade_points / ifo.total_ects, 2) as cgpa'),

            // I think this is all the semester count taken in that year, not sure yet
            // DB::raw('count(ifo.semester) as total_academic_periods'),
        )
        ->where('gl.certified_on', 'like', '%2023%')
        ->when(!empty($request->filter), function ($q) {
            return $q->where('gl.academic_year', request('academic_year'));
        })
        ->orderBy('s.student_id', 'desc')
        ->get();

        $request->flashExcept('_token');

        return view(
            'app.emis.student.graduate.index',
            compact('graduates', 'nation_institute_id', 'search')
        );
    }

    public function attrition(Request $request): View
    {
        $search = $request->get('search', '');
        $nation_institute_id = new NationInstitutionId;
        $attritions = DB::connection('mysql_srs')
        ->table('student as s')
        ->join('sf_guard_user as sf', 'sf.id', '=', 's.sf_guard_user_id')
        ->join('student_info as ifo', 's.id', '=', 'ifo.student_id')
        ->join('program as p', 'ifo.program_id', '=', 'p.id')
        ->join('department as d', 'd.id', '=', 'p.department_id')
        ->join('action_on_student as ac', 'ifo.laction', 'ac.id')
        ->select(
            's.student_id as stud_id',
            'd.department_code as institution_code',
            'ifo.academic_year',
            'ifo.semester AS academic_period', // later check where each academic period data code is stored, for now just the value
            'ac.action_code as attrition_reason',
            'ac.id as laction_id'
        )
        ->whereIn('ifo.laction', [3,4,7,11,10])
        ->where('ifo.academic_year', 'like', '2023/%')
        ->orderBy('s.student_id', 'desc')
        ->get();
        return view(
            'app.emis.student.attrition.index',
            compact('attritions', 'nation_institute_id', 'search')
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

    public function others(Request $request): View
    {
        $search = $request->get('search', '');

        return view(
            'app.emis.student.others.index'
        );
    }

    public function othersFilter(Request $request)
    {

        $academicYear = request('academic-year');
        $semester = request('semester');
        $year = request('year');

        $reportType = $request->input('report-type');

        $query = DB::connection('mysql_srs')
            ->table('student AS s')
            ->join('student_info AS sfo', 's.id', '=', 'sfo.student_id');

        if (!empty($academicYear)) {
            $query->where('sfo.academic_year', $academicYear);
        }

        if (!empty($semester)) {
            $query->where('sfo.semester', $semester);
        }

        if (!empty($year)) {
            $query->where('sfo.year', $year);
        }

        $query->select(
            's.birth_date',
            's.sex',
            'sfo.academic_year',
            DB::raw('YEAR(CURDATE()) - YEAR(s.birth_date) - (DATE_FORMAT(CURDATE(), "%m%d") < DATE_FORMAT(s.birth_date, "%m%d")) AS age')
        );

        // prevent logically impossible birth_date value from fetching
        $twoYearsAgo = now()->subYears(2);

        $query->whereNotNull('s.birth_date')
            ->where('s.birth_date', '<=', $twoYearsAgo);

        $query = $query->paginate(10);

        // student age by sex report type
        if($request->input('report-type') == 1){
            $output = '
                <tr>
                    <th rowspan="2">Age</th>
                    <th colspan="2">Sex</th>
                    <th rowspan="2">Male to Female Ration</th>
                </tr>
                <tr>
                    <th>M</th>
                    <th>F</th>
                </tr>
                ';

            $loop = 1;
            if($query->count() > 0){
                foreach ($query as $row) {
                    $output .= '
                        <tr>
                            <td>' . $row->age . '</td>
                            <td>' . $row->sex . '</td>
                            <td>' . $row->sex . '</td>
                            <td>' . $row->sex . '</td>
                        </tr>
                ';
                    $loop++;
                }
            }else{
                $output .= '<tr><th>No items found!</th></tr>';
            }

            // $data = [
            //     'data' => $output,
            //     'links' => $query->links(),
            // ];

            // return response()->json($data);
        }

        // completion rate of UG students in percent report type
        else{
            $output = '
                <tr>
                    <th rowspan="2">Program</th>
                    <th colspan="2">1st to 2nd Year</th>
                    <th colspan="2">2nd to 3rd Year</th>
                    <th colspan="2">3rd to 4th Year</th>
                    <th colspan="2">4th to 5th Year</th>
                    <th colspan="2">5th to 6th Year</th>
                    <th colspan="2">6th to 7th Year</th>
                </tr>
                <tr>
                    <th>M</th>
                    <th>F</th>
                    <th>M</th>
                    <th>F</th>
                    <th>M</th>
                    <th>F</th>
                    <th>M</th>
                    <th>F</th>
                    <th>M</th>
                    <th>F</th>
                    <th>M</th>
                    <th>F</th>
                </tr>
                ';

            $loop = 1;
            if($query->count() > 0){
                foreach ($query as $row) {
                    $output .= '
                        <tr>
                            <td>program name</td>
                            <td>' . $row->age . '</td>
                            <td>' . $row->sex . '</td>
                            <td>' . $row->sex . '</td>
                            <td>' . $row->sex . '</td>
                            <td>' . $row->sex . '</td>
                            <td>' . $row->sex . '</td>
                            <td>' . $row->sex . '</td>
                            <td>' . $row->sex . '</td>
                            <td>' . $row->sex . '</td>
                            <td>' . $row->sex . '</td>
                            <td>' . $row->sex . '</td>
                            <td>' . $row->sex . '</td>
                        </tr>
                ';
                    $loop++;
                }
            }else{
                $output .= '<tr><th>No items found!</th></tr>';
            }

            // $data = [
            //     'data' => $output,
            //     'links' => $query->links(),
            // ];

            // return response()->json($data);
        }

        $request->flash();

        return view(
            'app.emis.student.others.index', compact('output','query' , 'reportType')
        );
    }

}
