<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Wfh;
use App\Models\Requestdb;
use Illuminate\Support\Facades\Auth;

class WfhAdminController extends Controller
{
    public function update(Request $request, $id)
    {
        $wfh = Wfh::find($id);
        if ($wfh === null) {
            return $this->errorResponse("Wfh not found", 404);
        } else {
            $wfh->requests->first()->status = 'canceled';
            return $this->showCustom($wfh->requests->first(),200);
        }
    }
    public function destroy($id)
    {
        $wfh = Wfh::find($id);
        if ($wfh === null) {
            return $this->errorResponse("Wfh not found", 404);
        } else {
            $wfh->requests->first()->status = 'canceled';
            return $this->showCustom($wfh->requests->first(),200);
        }
    }
    public function showAllRequestes(){
        $request= Requestdb::whereHasMorph(
            'requestable',
            [Wfh::class]
        )->get();

    }
}
