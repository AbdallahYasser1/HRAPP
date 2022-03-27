<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wfh;

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
}
