<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function listMedicine()
    {
        $medicines = Medicine::with([
            "patient",
            "doctor",
        ])->get();

        return view('admin.pages.appointments.index',[
            "medicines" => $medicines,
        ]);
    }
    public function softDeleteMedicine($id)
    {
        $medicine = Medicine::find($id);
        $medicine->delete();

        return redirect()->route('admin.medicines')->with('success','Medicine deleted successfully');
    }
    public function restoreMedicine($id)
    {
        $medicine = Medicine::withTrashed()->find($id);
        $medicine->restore();

        return redirect()->route('admin.medicines')->with('success','Medicine restored successfully');
    }
    public function deleteMedicine($id)
    {
        $medicine = Medicine::withTrashed()->find($id);
        $medicine->forceDelete();

        return redirect()->route('admin.medicines')->with('success','Medicine deleted successfully');
    }
    public function manageMedicine(Request $request,$id)
    {
        $medicine = Medicine::findOrfail($id);
        if($request->has('status')){
            $medicine->status = $request->status;
            $medicine->save();
            return redirect()->route('admin.medicines')->with('success','Provider status updated successfully');
        }

        return redirect()->route('admin.providers')->with('error','Something went wrong');
    }
}
