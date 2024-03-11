<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $doctors = Doctor::when($request->input('name'), function ($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $file->storeAs('uploads', $filename, 'public');
            $data['photo'] = $filename;
        }
        $data['pid']=rand(0, 100);
        // dd($data);
        Doctor::create($data);
        return redirect()->route('doctors')->with('success', 'User successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $user = Doctor::findOrFail($id);
        return view('doctors.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        $request->validate([
            'nik' => 'required',
            'sip' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            // 'photo' => 'mimes:png,jpg,jpeg,gif|max:5000'
        ]);
        $user = Doctor::findOrFail($id);
        $user->nik = $request->nik;
        $user->sip = $request->sip;
        $user->name = $request->name;


        if ($request->file('photo')) {
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $file->storeAs('uploads', $filename, 'public');
            $user->photo = $filename;
        }
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->status = $request->status;
        $user->save();
        return redirect()->route('doctors')->with('success', 'User successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = Doctor::findOrFail($id);
        $user->delete();
        return redirect()->route('doctors')->with('success', 'User successfully deleted');
    }
}
