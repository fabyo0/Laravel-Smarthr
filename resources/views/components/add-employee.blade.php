<div id="add_employee" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="{{ route('employee.add') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="firstname" type="text" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Last Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="lastname" type="text" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                <input class="form-control" name="email" type="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Phone</label>
                                <input class="form-control" name="phone" type="text" maxlength="15">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Company <span class="text-danger">*</span></label>
                                <input class="form-control" name="company" type="text" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Department <span class="text-danger">*</span></label>
                                <select class="form-control" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Designation <span class="text-danger">*</span></label>
                                <select class="form-control" name="designation_id" required>
                                    <option value="">Select Designation</option>
                                    @foreach ($designations as $designation)
                                        @dump($designation)
                                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Avatar</label>
                                <input class="form-control" name="avatar" type="file" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
