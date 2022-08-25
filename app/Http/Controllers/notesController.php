<?php

namespace App\Http\Controllers;

use App\Models\note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class notesController extends Controller
{
    public function __construct()
    {
        $this->middleware('owner')->only('show','edit','destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes=note::where('user_id',Auth::user()->id)->get();
        return view('notes.index',compact(['notes']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEdit=false;
        return view('notes.create-edit',compact(['isEdit']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "title"=>"required|unique:notes,title",
            "description"=>"required"
        ]);
        $note=new note();
        $note->title=$request->title;
        $note->description=$request->description;
        $note->user_id=Auth::user()->id;
        $note->save();
        return redirect(route('notes.show',$note->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(note $note)
    {
        return view('notes.show',compact(['note']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(note $note)
    {
        $isEdit=true;
        return view('notes.create-edit',compact(['isEdit','note']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, note $note)
    {
        $request->validate([
            "title"=>"required",
            "description"=>"required"
        ]);
        $note->title=$request->title;
        $note->description=$request->description;
        $note->user_id=Auth::user()->id;
        $note->update();
        return redirect(route('notes.show',$note->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(note $note)
    {
        //dd($note);
        $note->delete();
        return redirect(route('home'));
    }
}
