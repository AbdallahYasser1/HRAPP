<?php

namespace App\Http\Controllers\Absence;

use Illuminate\Http\Request;
use App\Models\Absence;
use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserAbsenceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $absences = $user->absences;
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
    public function show($user_id, $absence_id)
    {
        $user = User::findOrFail($user_id);
        $absence = $user->absences()->findOrFail($absence_id);
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
    public function destroy($user_id, $absence_id)
    {
        $user = User::findOrFail($user_id);
        $absence = $user->absences()->findOrFail($absence_id);
        $absence->delete();
        return $this->showOne($absence);
    }

    public function getUserAbsenceByDate(Request $request, $user_id)
    {
        $rules = [
            'date' => 'required|date',
        ];
        $this->validate($request, $rules);
        $user = User::findOrFail($user_id);
        $absences = $user->absences()->where('date', $request->date)->get();
        return $this->showAll($absences);
    }
}
