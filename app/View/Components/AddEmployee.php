<?php

namespace App\View\Components;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Project;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class AddEmployee extends Component
{
    public Collection $departments;
    public Collection $designations;
    public Collection $projects;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->projects = Project::with(['leader.department'])->get();
        $this->departments = Department::all();
        $this->designations = Designation::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.add-employee');
    }
}
