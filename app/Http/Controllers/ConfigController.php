<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigRequest;
use App\Models\Config;
use Cloudinary\Api\ApiResponse;
use Illuminate\Http\Request;

class   ConfigController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $config = Config::all();
        return $this->showCustom($config, 200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConfigRequest $request)
    {
        if ($request->hasFile('photo')) {
            //$path=$request->file('photo')->store('public/images');
            $path = cloudinary()->upload($request->file('photo')->getRealPath())->getSecurePath();

            $config = Config::create([
                'company_name' => $request['company_name'], 'branches' => $request['branches'], 'specifity' => $request['specifity'], 'company_email' => $request['company_email'], 'company_phone' => $request['company_phone'], 'location' => $request['location'], 'country' => $request['country'], 'photo' => $path, 'latiude' => $request['latiude'],
                'longtiude' => $request['longtiude'], 'distance' => $request['distance'],
                'timezone'=>$request['timezone'],'fullDayAbsenceDeduction'=>$request['fullDayAbsenceDeduction'],'halfDayAbsenceDeduction'=>$request['fullDayAbsenceDeduction'],'fullDayAbsenceDeductionName'=>$request['fullDayAbsenceDeductionName'],'halfDayAbsenceDeductionName'=>$request['halfDayAbsenceDeductionName']
            ]);
            return $this->showCustom($config, 200);
        } else {
            return $this->errorResponse('photo not found', 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $config = Config::find($id);
        if ($config == null) return $this->errorResponse('Config Not Fouund', 404);
        else {
            return $this->showOne($config, 200);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $config = Config::find($id);
        if ($config == null) return $this->errorResponse('Config Not Fouund', 404);
        else {
            $config->update($request->all());
            return $this->showOne($config, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $config = Config::find($id);
        if ($config == null) return $this->errorResponse('Config Not Fouund', 404);
        else {
            $config->delete();
            return $this->showCustom("config Deleted", 200);
        }
    }
}
