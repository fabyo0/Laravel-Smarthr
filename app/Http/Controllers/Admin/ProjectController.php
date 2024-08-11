<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Project\ProjectStoreRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Project;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'projects';
        $projects = Project::latest()->get();
        return view('backend.projects.index', compact(
            'title', 'projects'
        ));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $title = 'projects';
        $projects = Project::latest()->get();
        return view('backend.projects.list', compact(
            'title', 'projects'
        ));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View|Application|\Illuminate\View\View
     */
    public function leads()
    {
        $title = 'Project Leads';

        $projects = Project::with(['leader.department'])->get();

        return view('backend.projects.leads', [
            'title' => $title,
            'projects' => $projects,
            'departments' => Department::all(),
            'designations' => Designation::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProjectStoreRequest $request)
    {
        // File Upload
        $files = $this->handleFileUpload($request, $request->name);

        $validatedData = $request->validated();

        $validatedData['files'] = $files;

        Project::create($validatedData);

        $notification = notify('Project has been added successfully.');
        return back()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param string $project_name
     * @return \Illuminate\Http\Response
     */
    public function show($project_name)
    {
        $title = 'view project';
        $project = Project::where('name', '=', $project_name)->firstOrFail();
        return view('backend.projects.show', compact(
            'title', 'project'
        ));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectStoreRequest $request, $id)
    {
        $project = Project::query()->findOrFail($id);

        // File upload
        $files = $this->handleFileUpload($request, $project->name, $project->files);

        $validatedData = $request->validated();
        $validatedData['files'] = $files;

        $project->update($validatedData);

        $notification = notify('Project has been updated successfully.');
        return back()->with($notification);
    }


    private function handleFileUpload($request, $projectName, $existingFiles = [])
    {
        $files = $existingFiles;
        if ($request->hasFile('project_files')) {
            $files = [];
            foreach ($request->file('project_files') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->extension();
                $file->move(public_path('storage/projects/' . $projectName), $fileName);
                $files[] = $fileName;
            }
        }

        return $files;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $project = Project::query()->findOrFail($request->id);

        if ($project->files) {
            foreach ($project->files as $file) {
                $filePath = public_path('storage/projects/' . $project->name . '/' . $file);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        }

        $project->delete();
        return back()->with('success', 'Project has been deleted successfully!');
    }

}
