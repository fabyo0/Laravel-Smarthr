<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "employees";
        $designations = Designation::get();
        $departments = Department::get();
        $employees = Employee::with('department', 'designation')->get();
        return view('backend.employees',
            compact('title', 'designations', 'departments', 'employees'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $title = "employees";
        $designations = Designation::get();
        $departments = Department::get();
        $employees = Employee::with('department', 'designation')->get();
        return view('backend.employees-list',
            compact('title', 'designations', 'departments', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EmployeeStoreRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['avatar'] = $this->uploadAvatar($request);

        $validatedData['uuid'] = IdGenerator::generate([
            'table' => 'employees',
            'field' => 'uuid',
            'length' => 7,
            'prefix' => 'EMP-'
        ]);

        Employee::create($validatedData);

        return back()->with('success', 'Employee has been added');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'phone' => 'nullable|max:15',
            'company' => 'required|max:200',
            'avatar' => 'file|image|mimes:jpg,jpeg,png,gif',
            'department' => 'required',
            'designation' => 'required',
        ]);
        if ($request->hasFile('avatar')) {
            $imageName = time() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('storage/employees'), $imageName);
        } else {
            $imageName = Null;
        }

        $employee = Employee::find($request->id);
        $employee->update([
            'uuid' => $employee->uuid,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'department_id' => $request->department,
            'designation_id' => $request->designation,
            'avatar' => $imageName,
        ]);
        return back()->with('success', "Employee details has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $employee = Employee::find($request->id);
        $employee->delete();
        return back()->with('success', "Employee has been deleted");
    }


    private function uploadAvatar(Request $request)
    {
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'employees/' . $imageName;

            Storage::disk('public')->put($imagePath, file_get_contents($image));

            return $imageName;
        }

        return null;
    }
}
