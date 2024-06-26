@extends('app')

@section('content')
<head>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="css/popup.css">
</head>

<div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
    <div class="search" style="max-width: 300px;"> 
        <input id="searchInput" class="search-input" type="text" placeholder="search by name"></input>
        <span class="search-icon material-symbols-outlined" role="button" onclick="searchData()">Search</span>
    </div>
</div>

<div class="card">
    <div class="card-body">
    <h4 class="font-weight-bold mb-0">Data Akun Tamu VIP</h4>
    <br>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="dropdown">
                <button class="btn btn-dark dropdown-toggle" type="button" id="exportDropdownButton" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px;">
                    Rekap
                </button>
                <ul class="dropdown-menu" aria-labelledby="exportDropdownButton">
                    <li><a class="dropdown-item" href="{{ route('cetak-akun_vip') }}" target="_blank" id="exportPdfButton"><i class="fas fa-file-pdf"></i> PDF</a></li>
                    <li><a class="dropdown-item" href="{{ route('excel-akun') }}" id="exportExcelButton"><i class="fas fa-file-excel"></i> Excel</a></li>
                </ul>
            </div>
            <button class="btn btn-success" type="button" style="padding: 5px 10px; color: #fff; margin-right: 10px;" onclick="togglePopup()">
                <i class="fas fa-plus"></i> &nbsp;Tambah Data 
            </button>
        </div>


<!-- SECTION CONTAINER TABEL-->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="table-list">
                                <thead>
                                    <tr>
                                    <th>No.</th>
                                    <th>username</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Alamat</th>
                                    <th>No Telepon</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <!-- Looping through vips, but limited to 10 per page -->
                                   @php
                                    $currentPage = $users->currentPage() ?? 1; // Get current page
                                    $startNumber = ($currentPage - 1) * 10 + 1; // Calculate starting number
                                    @endphp
                                    <!-- Looping through profiles, but limited to 10 per page -->
                                    @foreach($users as $index => $user)
                                    <tr>
                                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->index + 1 }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->password }}</td>
                                        <td>{{ $user->alamat }}</td>
                                        <td>{{ $user->no_telepon }}</td>
                                        <td>{{ $user->tanggal_lahir }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>{{ $user->updated_at }}</td>
                                        <td class="d-flex align-items-center">
                                            <button onclick="togglePopupedit({{ $user->id }})" class="btn btn-primary" style="color: white; padding: 5px 10px; height: auto;"> 
                                                <i class="fas fa-edit"></i>&nbsp;Edit
                                            </button>&nbsp;
                                            <form action="{{ route('akun_vip.destroy', $user->id) }}" method="POST" class="delete-form">
                                            @method('delete')
                                            @csrf
                                            <button onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn btn-danger" style="color: white; padding: 5px 10px; height: auto;">
                                                <i class="fas fa-trash-alt"></i>&nbsp;Delete
                                            </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            
         <!-- Pagination -->
         <br></br>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
            <li class="page-item {{ ($users->onFirstPage()) ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $users->previousPageUrl() }}">Previous</a>
            </li>
            @for ($i = 1; $i <= $users->lastPage(); $i++)
            <li class="page-item {{ ($users->currentPage() == $i) ? 'active' : '' }}">
                <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
            </li>
            @endfor
            <li class="page-item {{ ($users->currentPage() == $users->lastPage()) ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $users->nextPageUrl() }}">Next</a>
            </li>
            </ul>
        </nav>
        <!-- End Pagination -->
   



<!-- POP UP TAMBAH DATA -->
<div id="popup" style="display: none; position: fixed; top: 55%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 20px; border-radius: 10px; border: 1px solid #ccc; /* abu-abu yang lebih muda */ box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); max-width: 400px; max-height: 80vh; overflow-y: auto; z-index: 9999;">
    <h4 style="margin-top: 0; margin-bottom: 20px; text-align: center;">Tambah Data Akun Tamu</h4>
    
    <form action="{{ route('akun_vip.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="username">username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username">
        </div>
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan asal email">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="text" class="form-control" id="password" name="password" placeholder="Masukkan password">
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat">
        </div>
        <div class="form-group">
            <label for="no_telepon">No. Telepon</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" placeholder="Masukkan no_telepon">
        </div>
        <div class="form-group">
            <label for="tanggal_lahir">tanggal_lahir</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="Masukkan tanggal_lahir">
        </div>
        
        <div style="text-align: center;">
            <button type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
            <button type="button" class="btn btn-secondary" onclick="togglePopup()">Close</button>
        </div>
    </form>
</div>
<!-- END POP UP TAMBAH DATA -->

<!-- POP UP EDIT DATA -->
@foreach($users as $user)
<div id="popupedit{{ $user->id }}" style="display: none; position: fixed; top: 55%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 20px; border-radius: 10px; border: 1px solid #ccc; /* abu-abu yang lebih muda */ box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); max-width: 400px; max-height: 80vh; overflow-y: auto; z-index: 9999;">
    <form action="{{ route('akun_vip.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
            <label for="username">username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" value="{{ $user->username }}">
        </div>
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama" value="{{ $user->name }}">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan asal email" value="{{ $user->email }}">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="text" class="form-control" id="password" name="password" placeholder="Masukkan password" value="{{ $user->password }}">
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat" value="{{ $user->alamat }}">
        </div>
        <div class="form-group">
            <label for="no_telepon">No. Telepon</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" placeholder="Masukkan no_telepon" value="{{ $user->no_telepon }}">
        </div>
        <div class="form-group">
            <label for="tanggal_lahir">tanggal_lahir</label>
            <input type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="Masukkan tanggal_lahir" value="{{ $user->tanggal_lahir }}">
        </div>
        
        <div style="text-align: center;">
            <button type="submit" class="btn btn-primary" style="margin-right: 10px;">Update</button>
            <button type="button" class="btn btn-secondary" onclick="togglePopupedit('{{ $user->id }}')">Close</button>
        </div>
    </form>
</div>
@endforeach
<!-- END POP UP EDIT DATA -->


<script>
    // Mendapatkan tombol "Report"
    var reportButton = document.getElementById('reportButton');

    // Mendapatkan tanggal hari ini
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
    var yyyy = today.getFullYear();

    today = mm + '/' + dd + '/' + yyyy;

    // Mengganti teks tombol dengan tanggal hari ini
    reportButton.innerHTML = today;

    // Export to PDF
    document.getElementById('exportPdfButton').addEventListener('click', function() {
        // Your logic for exporting to PDF goes here
        console.log('Export to PDF clicked');
    });

    // Export to Excel
    document.getElementById('exportExcelButton').addEventListener('click', function() {
        // Your logic for exporting to Excel goes here
        console.log('Export to Excel clicked');
    });

    // Function to toggle popup
    function togglePopup() {
        var popup = document.getElementById('popup');
        if (popup.style.display === 'none') {
            popup.style.display = 'block';
        } else {
            popup.style.display = 'none';
        }
    }

    // Function to toggle popup edit
    function togglePopupedit(id) {
        var popup = document.getElementById('popupedit' + id);
        if (popup.style.display === 'none') {
            popup.style.display = 'block';
        } else {
            popup.style.display = 'none';
        }
    }

    function fetchAllAkun_VipNames() {
        fetch("{{ route('all-akun_vip-names') }}")
            .then(response => response.json())
            .then(data => {
                const searchInput = document.getElementById("searchInput");
                data.forEach(name => {
                    const option = document.createElement("option");
                    option.value = name;
                    searchInput.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching visitor names:', error));
    }

    // Call the function to fetch all akun vip names when the page loads
    window.onload = function() {
        fetchAllAkun_VipNames();
    };

    // Function to search data by name
    function searchData() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput"); // Menggunakan ID searchInput
    filter = input.value.toUpperCase();
    table = document.getElementById("table-list");
    tbody = table.getElementsByTagName("tbody")[0];
    tr = tbody.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1]; // Index 1 is for the Name column
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>
@endsection
