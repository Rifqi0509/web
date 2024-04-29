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
    <h4 class="font-weight-bold mb-0">Data Tamu VIP</h4>
    <br>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdownButton" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px;">
                    Rekap
                </button>
                <ul class="dropdown-menu" aria-labelledby="exportDropdownButton">
                <li><a class="dropdown-item" href="{{ route('cetak-vip-form') }}" id="exportPdfButton"><i class="fas fa-file-pdf"></i> PDF</a></li>
                    <li><a class="dropdown-item" href="{{ route('excel-vip') }}" id="exportExcelButton"><i class="fas fa-file-excel"></i> Excel</a></li>
                </ul>
            </div>
            
            <button class="btn btn-dark" type="button" style="padding: 5px 10px; color: #fff; margin-right: 10px;" onclick="togglePopup()">
                <i class="fas fa-plus"></i> &nbsp;Tambah Data
            </button>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="table-list">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>KD.Undangan</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Keperluan</th>
                        <th>Asal Instansi</th>
                        <th>No HP</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Input Keterangan</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Looping through vips, but limited to 10 per page -->
                    @php
                    $currentPage = $vips->currentPage() ?? 1; // Get current page
                    $startNumber = ($currentPage - 1) * 10 + 1; // Calculate starting number
                    @endphp
                    <!-- Looping through vips, but limited to 10 per page -->
                    @foreach($vips as $index => $vip)
                    <tr>
                        <td>{{ ($vips->currentPage() - 1) * $vips->perPage() + $loop->index + 1 }}</td>
                        <td>{{ $vip->undangan }}</td>
                        <td>{{ $vip->nama }}</td>
                        <td>{{ $vip->alamat }}</td>
                        <td>{{ $vip->keperluan }}</td>
                        <td>{{ $vip->asal_instansi }}</td>
                        <td>{{ $vip->no_hp }}</td>
                        <td>{{ $vip->tanggal }}</td>
                        <td>
                        <select id="status-dropdown">
                            <option>Proses</option>
                            <option value="approved" class="approved">Approved</option>
                            <option value="rejected" class="rejected">Rejected</option>
                            <option value="pending" class="pending">Pending</option>
                        </select>
                        </td>
                        <td>
                            <input type="text" placeholder="Input Keterangan" />
                        </td>
                        <td>
                        <button onclick="editVisitor()" class="btn btn-success" style="color: white; padding: 5px 10px; height: auto;">
                            <i class="fas fa-edit"></i>&nbsp;Edit
                        </button>&nbsp;
                        <button onclick="deleteVisitor()" class="btn btn-danger" style="color: white; padding: 5px 10px; height: auto;">
                            <i class="fas fa-trash-alt"></i>&nbsp;Delete
                        </button>
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
            <li class="page-item {{ ($vips->onFirstPage()) ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $vips->previousPageUrl() }}">Previous</a>
            </li>
            @for ($i = 1; $i <= $vips->lastPage(); $i++)
            <li class="page-item {{ ($vips->currentPage() == $i) ? 'active' : '' }}">
                <a class="page-link" href="{{ $vips->url($i) }}">{{ $i }}</a>
            </li>
            @endfor
            <li class="page-item {{ ($vips->currentPage() == $vips->lastPage()) ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $vips->nextPageUrl() }}">Next</a>
            </li>
            </ul>
        </nav>
        <!-- End Pagination -->
    


<!-- POP UP TAMBAH DATA-->
<div id="popup" style="display: none; position: fixed; top: 56%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); max-width: 400px;">
    <h4 style="margin-top: 0; margin-bottom: 20px; text-align: center;">Tambah Data Tamu Kunjungan</h4>
    
    <form action="{{ route('vip.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama">Undangan</label>
            <input type="text" class="form-control" id="nama" name="undangan" placeholder="Masukkan Kode Undangan" required>
        </div>
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama" required>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat" required>
        </div>
        <div class="form-group">
            <label for="keperluan">Keperluan</label>
            <input type="text" class="form-control" id="keperluan" name="keperluan" placeholder="Masukkan keperluan" required>
        </div>
        <div class="form-group">
            <label for="asal_instansi">Asal Instansi</label>
            <input type="text" class="form-control" id="asal_instansi" name="asal_instansi" placeholder="Masukkan asal instansi" required>
        </div>
        <div class="form-group">
            <label for="no_hp">No HP</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukkan nomor HP" required pattern="08[0-9]{10,}">
        </div>

        <div class="form-group">
            <label for="status">Tanggal</label>
            <input type="date" class="form-control" id="status" name="tanggal" placeholder="Masukkan Tanggal" required>
        </div>
        <div style="text-align: center;">
            <button type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
            <button type="button" class="btn btn-secondary" onclick="togglePopup()">Close</button>
        </div>
    </form>
</div>

<!-- END POP UP TAMBAH DATA-->

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

    // Ambil elemen dropdown
var dropdown = document.getElementById("status-dropdown");

// Tambahkan event listener untuk menangani perubahan nilai dropdown
dropdown.addEventListener("change", function() {
    // Ambil nilai (value) dari opsi yang dipilih
    var selectedValue = dropdown.value;

    // Terapkan warna ke dropdown berdasarkan nilai yang dipilih
    switch (selectedValue) {
        case "pending":
            dropdown.style.backgroundColor = "yellow"; // Warna untuk pending
            break;
        case "approved":
            dropdown.style.backgroundColor = "green"; // Warna untuk approved
            break;
        case "rejected":
            dropdown.style.backgroundColor = "red"; // Warna untuk rejected
            break;
        default:
            dropdown.style.backgroundColor = ""; // Kembalikan ke warna default jika tidak ada nilai yang cocok
            break;
    }
});

        function editVisitor() {
            // Logika untuk mengedit data pengunjung
        }

        function deleteVisitor() {
            // Logika untuk menghapus data pengunjung
        }

    // Function to toggle popup
    function togglePopup() {
            var popup = document.getElementById('popup');
            if (popup.style.display === 'none') {
                popup.style.display = 'block';
            } else {
                popup.style.display = 'none';
            }
        }

        function fetchAllVipNames() {
    fetch("{{ route('all-vip-names') }}")
        .then(response => response.json())
        .then(data => {
            const searchInput = document.getElementById("searchInput");
            data.forEach(name => {
                const option = document.createElement("option");
                option.value = name;
                searchInput.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching vip names:', error));
}

function searchData() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("table-list");
    tbody = table.getElementsByTagName("tbody")[0];
    tr = tbody.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[2]; // Index 2 is for the Name column
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

// Call the function to fetch all VIP names when the page loads
window.onload = function() {
    fetchAllVipNames();
};

</script>
@endsection
