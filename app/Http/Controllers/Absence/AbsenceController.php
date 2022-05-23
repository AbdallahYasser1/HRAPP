<?php

namespace App\Http\Controllers\Absence;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absence;
use App\Http\Controllers\ApiController;

class AbsenceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $absences = Absence::all();
        return $this->showAll($absences);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $absence = Absence::findOrFail($id);
        return $this->showOne($absence);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $absence = Absence::findOrFail($id);
        $absence->delete();
        return $this->showOne($absence);
    }

    public function getAbsenceByDay(Request $request)
    {
        $rules = [
            'date' => 'required|date|date_format:Y-m-d',
        ];
        $this->validate($request, $rules);
        $absences = Absence::where('date', $request->date)->get();
        return $this->showAll($absences);
    }

    public function getAbsenceByMonth(Request $request)
    {
        $rules = [
            'date' => 'required|date_format:Y-m',
        ];
        $this->validate($request, $rules);
        $absences = Absence::where('date', 'like', $request->date . '%')->get();
        return $this->showAll($absences);
    }
}
