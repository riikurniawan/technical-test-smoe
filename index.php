<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee List</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Toastify -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body x-data="employeeApp()">
    <div class="container my-5">
        <h4 class="text-center">Employee List</h4>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>

        <!-- table list employee -->
        <table class="table" id="tableEmployee" x-init="loadEmployees">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Job Title</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(employee, index) in employees" :key="employee.id">
                    <tr>
                        <th scope="row" x-text="index + 1"></th>
                        <td x-text="employee.name"></td>
                        <td x-text="employee.job_title"></td>
                        <td>
                            <button type="button" class="edit btn btn-success" @click="editEmployee(employee)" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                            <button type="button" class="delete btn btn-danger" @click="deleteEmployee(employee)" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" id="name" class="form-control" x-model="newEmployee.name">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="job_title">Job title</label>
                        <input type="text" id="job_title" class="form-control" x-model="newEmployee.job_title">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="saveEmployee">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <input type="hidden" id="idEdit" x-model="currentEmployee.id">
                        <label for="nameEdit">Name</label>
                        <input type="text" id="nameEdit" class="form-control" x-model="currentEmployee.name">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="jobEdit">Job title</label>
                        <input type="text" id="jobEdit" class="form-control" x-model="currentEmployee.job_title">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="updateEmployee">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idDelete" x-model="currentEmployee.id">
                    Are you sure you want to delete this Employee Data: <b x-text="currentEmployee.name"></b>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastify -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Alpine.js script -->
    <script>
        function employeeApp() {
            return {
                employees: [],
                newEmployee: {
                    name: '',
                    job_title: ''
                },
                currentEmployee: {
                    id: '',
                    name: '',
                    job_title: ''
                },

                loadEmployees() {
                    axios.get('employee/index.php')
                        .then(response => {
                            this.employees = response.data;
                        })
                        .catch(error => {
                            console.error(error);
                        });
                },

                saveEmployee() {
                    // Reset error messages
                    document.getElementById('name').classList.remove('is-invalid');
                    document.getElementById('job_title').classList.remove('is-invalid');
                    document.querySelector('#name + .invalid-feedback').textContent = '';
                    document.querySelector('#job_title + .invalid-feedback').textContent = '';

                    axios.post('employee/save.php', this.newEmployee)
                        .then(response => {
                            if (response.data.status === 'error') {
                                // handle validation errors
                                if (response.data.msg && response.data.msg.name) {
                                    document.getElementById('name').classList.add('is-invalid');
                                    document.querySelector('#name + .invalid-feedback').textContent = response.data.msg.name;
                                }
                                if (response.data.msg && response.data.msg.job_title) {
                                    document.getElementById('job_title').classList.add('is-invalid');
                                    document.querySelector('#job_title + .invalid-feedback').textContent = response.data.msg.job_title;
                                }
                            }

                            if (response.data.status === 'success') {
                                const modalElement = document.getElementById('addModal');
                                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                modalInstance.hide();

                                this.loadEmployees();
                                Toastify({
                                    text: response.data.msg,
                                    duration: 3000
                                }).showToast();
                            }
                        });
                },

                editEmployee(employee) {
                    this.currentEmployee = {
                        ...employee
                    };
                },

                updateEmployee() {
                    // Reset error messages
                    document.getElementById('nameEdit').classList.remove('is-invalid');
                    document.getElementById('jobEdit').classList.remove('is-invalid');
                    document.querySelector('#nameEdit + .invalid-feedback').textContent = '';
                    document.querySelector('#jobEdit + .invalid-feedback').textContent = '';

                    axios.post(`employee/update.php?id=${this.currentEmployee.id}`, this.currentEmployee)
                        .then(response => {
                            if (response.data.status === 'error') {
                                // handle validation errors
                                if (response.data.msg && response.data.msg.name) {
                                    document.getElementById('nameEdit').classList.add('is-invalid');
                                    document.querySelector('#nameEdit + .invalid-feedback').textContent = response.data.msg.name;
                                }
                                if (response.data.msg && response.data.msg.job_title) {
                                    document.getElementById('jobEdit').classList.add('is-invalid');
                                    document.querySelector('#jobEdit + .invalid-feedback').textContent = response.data.msg.job_title;
                                }
                            }
                            if (response.data.status === 'success') {
                                const modalElement = document.getElementById('editModal');
                                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                modalInstance.hide();

                                this.loadEmployees();
                                Toastify({
                                    text: response.data.msg,
                                    duration: 3000
                                }).showToast();
                            }
                        });
                },

                deleteEmployee(employee) {
                    this.currentEmployee = {
                        ...employee
                    };
                },

                confirmDelete() {
                    axios.get(`employee/delete.php?id=${this.currentEmployee.id}`)
                        .then(response => {
                            if (response.data.status === 'error') {

                                this.loadEmployees();
                                Toastify({
                                    text: response.data.status,
                                    duration: 3000
                                }).showToast();
                            }
                            if (response.data.status === 'success') {
                                const modalElement = document.getElementById('deleteModal');
                                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                modalInstance.hide();

                                this.loadEmployees();
                                Toastify({
                                    text: response.data.msg,
                                    duration: 3000
                                }).showToast();
                            }
                        });
                }
            }
        }
    </script>
</body>

</html>