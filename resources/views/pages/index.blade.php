@extends('layout.layout')
@section('content')
<div class="mt-4">
            <div class="d-flex justify-content-between mb-3">
                <h3>Student Details</h3>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">Add</a>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" id="searchInput" placeholder="Search..." onkeyup="searchTable()">
            </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Mark</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                  @php  $i=1 ;@endphp
                @foreach($users as $user)
                @foreach($user->studentDetails as $detail)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $detail->subject }}</td>
                        <td>{{ $detail->mark }}</td>
                        <td>
                        <a href="#" class="btn btn-sm btn-warning" onclick="editItem({{ $detail->id }})">Edit</a>
                            <a href="#" class="btn btn-sm btn-danger"  onclick="deleteItem({{ $detail->id }})">Delete</a>
                        </td>
                    </tr>
                    @php  $i++ ;@endphp
                @endforeach
            @endforeach
                </tbody>
            </table>
            <!-- <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav> -->
        </div>
<div class="modal fade" id="addmodal" tabindex="-1" aria-labelledby="addmodalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addmodalLabel">Add</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    <div class="mb-3">
                        <label for="s_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="s_name" name="s_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="mark" class="form-label">Mark</label>
                        <input type="number" class="form-control" id="mark"  name="mark" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveButton">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addEditModal" tabindex="-1" aria-labelledby="addEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEditModalLabel">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="EditModal">
                    <div class="mb-3">
                        <label for="e_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="e_name" name="e_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="e_subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="e_subject" name="e_subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="e_mark" class="form-label">Mark</label>
                        <input type="number" class="form-control" id="e_mark" name="e_mark" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateButton">Update</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>
   document.addEventListener('DOMContentLoaded', function () {
    function deleteItem(id) {
    if (confirm("Are you sure you want to delete this?")) {
        fetch(`/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            updateTable(data);
        })
        .catch(error => console.error('Error deleting data:', error));
    }
}
    document.getElementById('saveButton').addEventListener('click', function () {
            const name = document.getElementById('s_name').value;
            const subject = document.getElementById('subject').value;
            const mark = document.getElementById('mark').value;

            if (name === '' || subject === '' || mark === '') {
                alert('Please fill in all fields.');
                return;
            }

            fetch('/student', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    name: name,
                    subject: subject,
                    mark: mark
                })
            })
            .then(response => response.json())
            .then(users => {
               
                updateTable(users); 
                document.getElementById('addForm').reset(); 
                const modal = bootstrap.Modal.getInstance(document.getElementById('addmodal'));
                modal.hide();
            })
            .catch(error => console.error('Error adding data:', error));
        });

        function updateTable(users) {
    const tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = ''; 
    var k=1;
    users.forEach((item) => {
      
        item.student_details.forEach((detail, i) => {
            const newRow = tableBody.insertRow();
            newRow.insertCell(0).innerText = k++;
            newRow.insertCell(1).innerText = item.name;
            newRow.insertCell(2).innerText = detail.subject;
            newRow.insertCell(3).innerText = detail.mark;
            newRow.insertCell(4).innerHTML = `
                <a href="#" class="btn btn-sm btn-warning" onclick="editItem(${detail.id})">Edit</a>
                <a href="#" class="btn btn-sm btn-danger" onclick="deleteItem(${detail.id})">Delete</a>
            `;
        });
    });
}

        window.editItem = function (id) {
        fetch(`/edit/${id}`)
            .then(response => response.json())
            .then(eusers => {
                populateForm(eusers);
                const modalElement = document.getElementById('addEditModal');
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            })
            .catch(error => console.error('Error fetching data:', error));
    };

    function populateForm(eusers) {
    document.getElementById('e_name').value = eusers.user ? eusers.user.name : '';
    document.getElementById('e_subject').value = eusers.subject;
    document.getElementById('e_mark').value = eusers.mark;
}

 document.getElementById('updateButton').addEventListener('click', function () {
            const name = document.getElementById('e_name').value;
            const subject = document.getElementById('e_subject').value;
            const mark = document.getElementById('e_mark').value;

            if (name === '' || subject === '' || mark === '') {
                alert('Please fill in all fields.');
                return;
            }

            fetch('/student', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    name: name,
                    subject: subject,
                    mark: mark
                })
            })
            .then(response => response.json())
            .then(users => {
                //
                updateTable(users); 
                document.getElementById('EditModal').reset(); 
                const modal = bootstrap.Modal.getInstance(document.getElementById('addEditModal'));
                modal.hide();
            })
            .catch(error => console.error('Error adding data:', error));
        });
        window.deleteItem = function (id) {
    if (confirm("Are you sure you want to delete this?")) {
        fetch(`/delete/${id}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateTable(data.users); 
            } else {
                alert(data.message || 'An error occurred while deleting the item.');
            }
        })
        .catch(error => console.error('Error deleting data:', error));
    }
};

window.searchTable = function() {
    const searchInput = document.getElementById('searchInput').value.trim();

    fetch(`/search?query=${encodeURIComponent(searchInput)}`)
        .then(response => response.json())
        .then(data => {
            updateTable(data);
        })
        .catch(error => console.error('Error fetching search results:', error));
}
    });
</script>
@endsection
