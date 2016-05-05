<?php

namespace App\Http\Controllers;

use App\Fileentry;
use Storage;
use File;
use Response;
use Illuminate\Http\Request;

use App\Http\Requests;

class FileEntryController extends Controller
{

    public function get($filename){
    
        $entry = Fileentry::where('filename', '=', $filename)->firstOrFail();
        // dd($entry);
        // $file = Storage::disk('local')->get($entry->filename);
        // dd(Storage::disk('local'));
        $path = public_path() .'/uploads/';
        $file = $path . $entry->original_filename;
        return response()
            ->file($file);
            // ->header('Content-Type', $entry->mime);
        // return (new Response($file, 200))
        //       ->header('Content-Type', $entry->mime);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entries = Fileentry::all();
        return view('fileentries.index', compact('entries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // // Determining If A File Was Uploaded
        // if ($request->hasFile('filefield'))
        // {
        //     //
        // }

        // // Determining If An Uploaded File Is Valid
        // if ($request->file('filefield')->isValid())
        // {
        //     //
        // }

        $files = $request->file('images');
        foreach($files as $file) {
            $destinationPath = public_path() .'/uploads/';
            $original_name = $file->getClientOriginalName();
            $name = $file->getFilename();
            $extension = $file->getClientOriginalExtension();
            $size = $file->getSize();

            $file->move($destinationPath, $original_name);

            $entry = new Fileentry();
            $entry->mime = $file->getClientMimeType();
            $entry->original_filename = $original_name;
            $entry->filename = $name . '.' . $extension;
     
            $entry->save();
        }
        
 
        return redirect('fileentry');
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
        //
    }
}
