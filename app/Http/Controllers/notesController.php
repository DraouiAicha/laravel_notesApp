<?php

namespace App\Http\Controllers;

use App\Models\note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\LaravelAdapter;

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
        $other=Auth::user()->shared;
        $notes=$notes->merge($other);
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
            "description"=>"required",
            "share"=>"required"
        ]);
        $note=new note();
        $note->title=$request->title;
        $note->description=$request->description;
        $note->user_id=Auth::user()->id;
        $note->save();
        $note->shared()->attach($request->share);
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
            "description"=>"required",
            "share"=>"required"
        ]);
        
        $note->title=$request->title;
        $note->description=$request->description;
        $note->user_id=Auth::user()->id;
        $note->update();
        $note->shared()->detach();
        $note->shared()->attach($request->share);
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
    public function getData()
    {
        /*$sqlBuilder=note::select([
            'notes.id',
            'notes.title',
            'users.name',
            'notes.created_at'
        ])
        ->join('users','users.id','notes.user_id')
        ->where('user_id',Auth::id());*/
        $sqlBuilder='SELECT a.id,a.title,a.name,a.created_at FROM((SELECT notes.id,notes.title,users.name,notes.created_at
        FROM notes notes
        JOIN users users ON users.id=notes.user_id
        WHERE notes.user_id='.Auth::id().')
        UNION
        (
            SELECT notes.id,notes.title,
            (SELECT name FROM users WHERE users.id=notes.user_id) AS name,
            notes.created_at
            FROM note_user note_user
            JOIN notes notes ON notes.id=note_user.note_id
            JOIN users users ON users.id=note_user.user_id
            WHERE note_user.user_id='.Auth::id().'
        )) a';
        $dt = new Datatables(new LaravelAdapter);
        $dt->query($sqlBuilder);
        $dt->edit('title', function ($data) {
            return '<a href="'.route('notes.show',$data['id']).'">'.$data['title'].'</a> ';
        });
        $dt->add('action', function ($data) {
            return '<a href="'.route('notes.edit',$data['id']).'">#edit </a> '.'/ <a href="'.route('notes.destroy',$data['id']).'">#delete </a> ';
        });         
        $dt->hide('id');
        return $dt->generate();
    }
}
