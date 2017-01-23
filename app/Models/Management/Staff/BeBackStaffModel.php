<?php

namespace App\Models\Management\Staff;

use App\Eloquents\Admin;
use Illuminate\Database\Eloquent\Model;

class BeBackStaffModel extends Model
{
    //



    public function getDaletedStaff()
    {
        return Admin::onlyTrashed()->get();
    }



    public function beBack($id)
    {

        try{
            Admin::where('id', '=', $id)->restore();
        } catch(\Exception $e) {
            return false;
        }
        return true;

    }
}
