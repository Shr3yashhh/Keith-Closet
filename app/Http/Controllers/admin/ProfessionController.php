<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfesstionRequest;
use App\Http\Requests\UpdateProfesstionRequest;
use App\Models\Profession;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $professions = Profession::all();
        return view('admin.pages.professions.index',['professions' => $professions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.professions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProfesstionRequest $request)
    {
        if($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $fileName = md5(microtime()).'_'.$file->getClientOriginalName();
            $file->move(public_path('profession_avatar'),$fileName);
           
        }
        $profession = new Profession();
        $profession->name = $request->name;
        $profession->meta_description = $request->meta_description;
        $profession->description = $request->description;
        if($request->hasFile('avatar')){
            $profession->avatar = $fileName;
        }
        $profession->save();
       
        return redirect()->route('professions.index')->with('success','Profession created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $profession = Profession::findOrFail($id);
        return view('admin.pages.professions.edit',['profession' => $profession]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfesstionRequest $request, $id)
    {   $profession = Profession::findOrFail($id);
        

        if($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $fileName = md5(microtime()).'_'.$file->getClientOriginalName();
            $file->move(public_path('profession_avatar'),$fileName);
            if($profession->avatar != null){
                $old_avatar = $profession->avatar;
                if(file_exists(public_path('profession_avatar/'.$old_avatar))){
                    unlink(public_path('profession_avatar/'.$old_avatar));
                }
             }
           
        }

        $profession->name = $request->name;
        $profession->meta_description = $request->meta_description;
        $profession->description = $request->description;
        if($request->hasFile('avatar')){
            $profession->avatar = $fileName;
        }
        $profession->save();
        return redirect()->route('professions.index')->with('success','Profession updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Profession::findOrFail($id)->delete();
        return redirect()->route('professions.index')->with('success','Profession deleted successfully');
    }
    public function manageProfession($id)
    {
       
        $profession = Profession::findOrFail($id);
        if($profession->status == 1){
            $profession->status = 0;
        }else{
            $profession->status = 1;
        }
        $profession->save();
        return redirect()->route('professions.index')->with('success','Profession status updated successfully');
    }
}
