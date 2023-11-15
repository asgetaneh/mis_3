<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\View\View;


class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $feedback = Feedback::all();
        $search = $request->get('search', '');

        $feedback = Feedback::latest()
            ->paginate(10)
            ->withQueryString();
        return view('app.feedback.index', compact('feedback'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        //$this->authorize('create', Feedback::class);        

        return view('app.feedback.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->input();
         try {
            $Feedback = new Feedback;
            $Feedback->username= "";
            $Feedback->description= $data['fback'];
            $Feedback->created_at= new \DateTime();
            $Feedback->updated_at =new \DateTime();
            $Feedback->save();
            
         return redirect()
            ->route('feedback')
            ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            return redirect('feedback')->withErrors(['errors' => $e]);
            }
    return view('landing.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feedback $feedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $feedback)
    {
        //
    }
}
