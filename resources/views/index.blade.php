<!DOCTYPE html>
<html>
   <head>
      <title>Assignment for Shyam Future Tech </title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   </head>
   <body>
      <div class="container mt-5">
         <div class="row">
            <div class="col-md-12">
               <h2>Add New Entry</h2>
               <div id="successMessage" class="mt-3 alert alert-success d-none"></div>
               <form id="addForm" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="row mb-3">
                     <label for="name" class="col-sm-2 col-form-label">Name</label>
                     <div class="col-sm-4">
                        <input type="text" class="form-control" id="name" name="name">
                        <div class="invalid-feedback" id="nameError"></div>
                     </div>
                  </div>
                  <div class="row mb-3">
                     <label for="image" class="col-sm-2 col-form-label">Image</label>
                     <div class="col-sm-4">
                        <input type="file" class="form-control" id="image" name="image">
                        <div class="invalid-feedback" id="imageError"></div>
                     </div>
                  </div>
                  <div class="row mb-3">
                     <label for="address" class="col-sm-2 col-form-label">Address</label>
                     <div class="col-sm-4">
                        <textarea class="form-control" id="address" name="address"></textarea>
                        <div class="invalid-feedback" id="addressError"></div>
                     </div>
                  </div>
                  <div class="row mb-3">
                     <label for="gender" class="col-sm-2 col-form-label">Gender</label>
                     <div class="col-sm-4">
                        <select class="form-select" id="gender" name="gender">
                           <option value="">--Select Gender--</option>
                           <option value="Male">Male</option>
                           <option value="Female">Female</option>
                        </select>
                        <div class="invalid-feedback" id="genderError"></div>
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="col-sm-2"></div>
                     <div class="col-sm-4">
                        <button type="submit" class="btn btn-primary">Add</button>
                     </div>
                  </div>
               </form>
            </div>
            <div class="col-md-12">
               <h2>Data List</h2>
               <div class="d-flex mb-3">
                  <button class="btn btn-secondary me-2" onclick="sortData('name')">Sort by Name</button>
                  <button class="btn btn-secondary" onclick="sortData('id')">Sort by ID</button>
               </div>
               <div class="container" id="dataList">
                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th>ID</th>
                           <th>Name</th>
                           <th>Image</th>
                           <th>Address</th>
                           <th>Gender</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
      <!-- Edit Modal -->
      <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="editModalLabel">Edit Entry</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <form id="editForm" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="mb-3">
                        <label for="editId" class="form-label">ID</label>
                        <input type="text" class="form-control" id="editId" name="id" readonly>
                     </div>
                     <div class="mb-3">
                        <label for="editName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editName" name="name">
                     </div>
                     <div class="mb-3">
                        <label for="editImage" class="form-label">Image</label>
                        <input type="file" class="form-control" id="editImage" name="image">
                     </div>
                     <div class="mb-3">
                        <label for="editAddress" class="form-label">Address</label>
                        <textarea class="form-control" id="editAddress" name="address"></textarea>
                     </div>
                     <div class="mb-3">
                        <label for="editGender" class="form-label">Gender</label>
                        <select class="form-select" id="editGender" name="gender">
                           <option value="Male">Male</option>
                           <option value="Female">Female</option>
                        </select>
                     </div>
                     <button type="submit" class="btn btn-primary">Save Changes</button>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- View Modal -->
      <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="viewModalLabel">View Entry</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body" id="viewContent"></div>
            </div>
         </div>
      </div>
      <script>
         $(document).ready(function () {
             fetchData();
         
             $.ajaxSetup({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
             });
         
             $('#addForm').on('submit', function (e) {
                 e.preventDefault();
                 
                 let formData = new FormData(this);
                 $.ajax({
                     url: '/add',
                     method: 'POST',
                     data: formData,
                     contentType: false,
                     processData: false,
                     success: function (response) {
                         $('#successMessage').text(response.success).removeClass('d-none');
                         fetchData();
                         clearForm('#addForm');
                     },
                     error: function (response) {
                         handleErrors(response.responseJSON.errors);
                     }
                 });
             });
         
             $('#editForm').on('submit', function (e) {
                 e.preventDefault();
                 let formData = new FormData(this);
                 $.ajax({
                     url: '/edit',
                     method: 'POST',
                     data: formData,
                     contentType: false,
                     processData: false,
                     success: function (response) {
                         $('#editModal').modal('hide');
                         $('#successMessage').text(response.success).removeClass('d-none');
                         fetchData();
                     },
                     error: function (response) {
                         handleErrors(response.responseJSON.errors);
                     }
                 });
             });
         });
         
         function fetchData() {
         $.ajax({
             url: '/fetch', 
             method: 'GET',
             success: function (response) {
         var tableBody = $('#dataList tbody'); // Get the tbody element of the table
         
         // Clear existing data
         tableBody.html(''); 
         
         response.data.forEach(entry => {
         var tableRow = `
         <tr id="entry-${entry.id}">
         <td>${entry.id}</td>
         <td>${entry.name}</td>
         <td><img width="150px" height="150px" src="{{ url('storage/')}}/${entry.image}" alt="${entry.name}" class="img-fluid"></td>
         <td>${entry.address}</td>
         <td>${entry.gender}</td>
         <td>
           <button class="btn btn-primary" onclick="editEntry(${entry.id})">Edit</button>
           <button class="btn btn-danger" onclick="deleteEntry(${entry.id})">Delete</button>
           <button class="btn btn-info" onclick="viewEntry(${entry.id})">View</button>
         </td>
         </tr>
         `;
         
         tableBody.append(tableRow); // Add the row to the table body
         });
         }
         
         });
         }
         
         
         function sortData(sortBy) {
             $.ajax({
                 url: '/sort',
                 method: 'POST',
                 data: { sort_by: sortBy },
                 success: function (response) {
         var tableBody = $('#dataList tbody'); // Get the tbody element of the table
         
         // Clear existing data
         tableBody.html(''); 
         
         response.data.forEach(entry => {
         var tableRow = `
         <tr id="entry-${entry.id}">
         <td>${entry.id}</td>
         <td>${entry.name}</td>
         <td><img width="150px" height="150px" src="{{ url('storage/')}}/${entry.image}" alt="${entry.name}" class="img-fluid"></td>
         <td>${entry.address}</td>
         <td>${entry.gender}</td>
         <td>
           <button class="btn btn-primary" onclick="editEntry(${entry.id})">Edit</button>
           <button class="btn btn-danger" onclick="deleteEntry(${entry.id})">Delete</button>
           <button class="btn btn-info" onclick="viewEntry(${entry.id})">View</button>
         </td>
         </tr>
         `;
         
         tableBody.append(tableRow); // Add the row to the table body
         });
         }
         
             });
         }
         
         function clearForm(formSelector) {
             $(formSelector).find('input, textarea').val('');
             $(formSelector).find('select').prop('selectedIndex', 0);
         }
         
         function handleErrors(errors) {
             if (errors.id) {
                 $('#id').addClass('is-invalid');
                 $('#idError').text(errors.id[0]);
             }
             if (errors.name) {
                 $('#name').addClass('is-invalid');
                 $('#nameError').text(errors.name[0]);
             }
             if (errors.image) {
                 $('#image').addClass('is-invalid');
                 $('#imageError').text(errors.image[0]);
             }
             if (errors.address) {
                 $('#address').addClass('is-invalid');
                 $('#addressError').text(errors.address[0]);
             }
             if (errors.gender) {
                 $('#gender').addClass('is-invalid');
                 $('#genderError').text(errors.gender[0]);
             }
         }
         
         function editEntry(id) {
             $.ajax({
                 url: '/view',
                 method: 'POST',
                 data: { id: id },
                 success: function (response) {
                     let entry = response.data[0];
                     $('#editId').val(entry.id);
                     $('#editName').val(entry.name);
                     $('#editAddress').val(entry.address);
                     $('#editGender').val(entry.gender);
                     $('#editModal').modal('show');
                 }
             });
         }
         
         function deleteEntry(id) {
            $.ajax({
                url: '/delete',
                method: 'POST',
                data: { id: id },
                success: function (response) {
                    $(`#entry-${id}`).remove();
                    fetchData();

                    $('#successMessage').text(response.success).removeClass('d-none');
                }
            });
        }

         
         function viewEntry(id) {
         $.ajax({
             url: '/view',
             method: 'POST',
             data: { id: id },
             success: function (response) {
                 const entry = response.data[0]; 
                 $('#viewContent').html(`
                     <h5>ID: ${entry.id}</h5>
                     <p>Name: ${entry.name}</p>
                     <p>Address: ${entry.address}</p>
                     <p>Gender: ${entry.gender}</p>
                     <img src="${entry.image}" class="img-fluid rounded" alt="${entry.name}">
                 `);
                 $('#viewModal').modal('show');
             },
             error: function (error) {
                 console.error('Error:', error);
             }
         });
         }
         
         
      </script>
   </body>
</html>