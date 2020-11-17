<?php

namespace App\Http\Controllers;

use App\Session;
use App\Speaker;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SpeakerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $speaker = Speaker::all();
        return view('speaker.index', compact('speaker'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $speakers = Speaker::all();
        return view('speaker.create', compact('speakers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'avatar' => 'image|mimes:jpeg,png,jpg,gif',
            'name' => 'required',
            'link' => 'required',
            'info' => 'required',
        ]);

        $data['link'] = str_replace('*www.', '', $data['link']);


        if ($request->hasFile('avatar')){
            $images = $request->avatar->getClientOriginalExtension();
            $fileName = time().'.'.$images;
            $request->avatar->move('public/uploads/avatars', $fileName);
            $data['avatar'] = $fileName;
        }
        else{
            $data['avatar'] = 'default.jpg';
        }
        Speaker::create($data);

        $speaker = Speaker::all();

        return redirect()->route('speaker.index', compact('speaker'))->with([
            'message' => 'Speaker success created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Speaker $speaker
     * @return void
     */
    public function show(Speaker $speaker)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Speaker $speaker
     * @return Response
     */
    public function edit(Speaker $speaker)
    {
        return view('speaker.edit', compact('speaker'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Speaker $speaker
     * @return Response
     */
    public function update(Request $request, Speaker $speaker)
    {
        $data = $request->validate([
            'avatar' => 'image|mimes:jpeg,png,jpg,gif',
            'name' => 'required',
            'link' => 'required',
            'info' => 'required',
        ]);

        $data['link'] = str_replace_first('https://', '', $data['link']);

        if ($request->hasFile('avatar')){

            File::delete(public_path() . '\uploads\avatars\\' . $speaker->avatar);
            $images = $request->avatar->getClientOriginalExtension();
            $fileName = time().'.'.$images;
            $request->avatar->move('public/uploads/avatars', $fileName);
            $data['avatar'] = $fileName;
        }
        else{
            $data['avatar'] = $speaker->avatar;
        }

        $speaker->update($data);

        $speaker = Speaker::all();
        return redirect()->route('speaker.index', compact('speaker'))->with([
            'message' => 'Speaker success updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Speaker $speaker
     * @return Response
     * @throws \Exception
     */
    public function destroy(Speaker $speaker)
    {
        if ($speaker->speaker_session()->count() != 0)
            return back()->with([
                'message' => 'Speaker already used'
            ]);

        $speaker->delete();

        return redirect()->route('speaker.index')->with([
            'message' => 'Speaker successfully deleted'
        ]);
    }
}
