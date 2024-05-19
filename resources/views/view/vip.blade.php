@extends('app')

@section('content')
<head>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
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
                <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdownButton" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px; color:#55552b;">
                    Rekap
                </button>
                <ul class="dropdown-menu" aria-labelledby="exportDropdownButton">
                <li><a class="dropdown-item" href="{{ route('cetak-vip-form') }}" id="exportPdfButton"><i class="fas fa-file-pdf"></i> PDF</a></li>
                    <li><a class="dropdown-item" href="{{ route('excel-vip') }}" id="exportExcelButton"><i class="fas fa-file-excel"></i> Excel</a></li>
                </ul>
            </div>
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
                        <th>Rencana Tanggal Pertemuan</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Departemen</th>
                        <th>Seksi</th>
                        <th>Keterangan</th>
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
                        <td>{{ $vip->kd_undangan }}</td>
                        <td>{{ $vip->nama }}</td>
                        <td>{{ $vip->alamat }}</td>
                        <td>{{ $vip->keperluan }}</td>
                        <td>{{ $vip->asal_instansi }}</td>
                        <td>{{ $vip->no_hp }}</td>
                        <td>{{ $vip->tanggal }}</td>
                        <td>{{ $vip->jam }}</td>
                        <td>{{ $vip->status }}</td>
                        <td>{{ $vip->departemen }}</td>
                        <td>{{ $vip->seksi }}</td>
                        <td>{{ $vip->ket }}</td>
                      
                        
                        <td>
    <div style="display: flex; align-items: center;">
        <button onclick="togglePopupedit({{ $vip->id }})" class="btn btn-primary" style="color: white; padding: 5px 10px; height: auto;">
            <i class="fas fa-edit"></i>&nbsp;Edit
        </button>
        <form action="{{ route('vip.destroy', $vip->id) }}" method="POST" class="delete-form" style="margin-left: 5px;">
            @method('delete')
            @csrf
            <button onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn btn-danger" style="color: white; padding: 5px 10px; height: auto;">
                <i class="fas fa-trash-alt"></i>&nbsp;Delete
            </button>
        </form>
    </div>
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
    
<!-- END POP UP TAMBAH DATA-->
@foreach($vips as $vip)

<!-- POP UP Edit DATA-->
<div id="popupedit{{ $vip->id }}" style="display: none; position: fixed; top: 55%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 20px; border-radius: 10px; border: 1px solid #ccc; /* abu-abu yang lebih muda */ box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); max-width: 400px; max-height: 80vh; overflow-y: auto; z-index: 9999;">
    <!-- Konten Pop-up Edit -->
    <h4 style="margin-top: 0; margin-bottom: 20px; text-align: center;">Edit Data Tamu VIP</h4>
    
    <form action="{{ route('vip.update', $vip->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="kd_undangan">Kode Undangan</label>
            <input type="text" class="form-control" id="kd_undangan" name="kd_undangan" placeholder="Masukkan kd_undangan" value="{{ $vip->kd_undangan }}">
        </div>

        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama" value="{{ $vip->nama }}">
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat" value="{{ $vip->alamat }}">
        </div>
        <div class="form-group">
            <label for="keperluan">Keperluan</label>
            <input type="text" class="form-control" id="keperluan" name="keperluan" placeholder="Masukkan keperluan" value="{{ $vip->keperluan }}">
        </div>
        <div class="form-group">
            <label for="asal_instansi">Asal Instansi</label>
            <input type="text" class="form-control" id="asal_instansi" name="asal_instansi" placeholder="Masukkan asal instansi" value="{{ $vip->asal_instansi }}">
        </div>
        <div class="form-group">
            <label for="no_hp">No HP</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukkan nomor HP" value="{{ $vip->no_hp }}">
        </div>
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" placeholder="Masukkan Tanggal" value="{{ $vip->tanggal }}">
        </div>
        <div class="form-group">
            <label for="jam">Jam</label>
            <input type="time" class="form-control" id="jam" name="jam" placeholder="Masukkan Jam" value="{{ $vip->jam }}">
        </div>
        <div class="form-group">
            <label for="departemen">Departemen</label>
            <select class="form-control" id="departemen" name="departemen">
                <option value="keuangan" {{ $vip->departemen == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                <option value="ketenagakerjaan" {{ $vip->departemen == 'ketenagakerjaan' ? 'selected' : '' }}>Ketenagakerjaan</option>
                <option value="paud/tk" {{ $vip->departemen == 'paud/tk' ? 'selected' : '' }}>Paud/TK</option>
                <option value="sd" {{ $vip->departemen == 'sd' ? 'selected' : '' }}>SD</option>
                <option value="smp" {{ $vip->departemen == 'smp' ? 'selected' : '' }}>SMP</option>
                <option value="perencanaan" {{ $vip->departemen == 'perencanaan' ? 'selected' : '' }}>Perencanaan</option>
            </select>
        </div>

        <div class="form-group">
            <label for="seksi">Seksi</label>
            <select class="form-control" id="seksi" name="seksi">
            <option value="kurikulum/penilaian" {{ $vip->seksi == 'kurikulum/penilaian' ? 'selected' : '' }}>Kurikulum/Penilaian</option>
            <option value="sarana/prasarana" {{ $vip->seksi == 'sarana/prasarana' ? 'selected' : '' }}>Sarana/Prasarana</option>
            <option value="pendidik_sd" {{ $vip->seksi == 'pendidik_sd' ? 'selected' : '' }}>Pendidik dan Tenaga SD</option>
            <option value="pendidik_smp" {{ $vip->seksi == 'pendidik_smp' ? 'selected' : '' }}>Pendidik dan Tenaga Smp</option>
            </option>
            <!-- Tambahkan opsi lainnya sesuai dengan kebutuhan -->
        </select>
        </div>
        <div class="form-group">
    <label for="status">Status</label>
    <select class="form-control" id="status" name="status">
        <option value="Proses" {{ $vip->status == 'Proses' ? 'selected' : '' }}>Proses</option>
        <option value="Approved" {{ $vip->status == 'Approved' ? 'selected' : '' }}>Approved</option>
        <option value="Rejected" {{ $vip->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
        <option value="Pending" {{ $vip->status == 'Pending' ? 'selected' : '' }}>Pending</option>
    </select>
</div>

        <div class="form-group">
            <label for="ket">Keterangan</label>
            <input type="text" class="form-control" id="ket" name="ket" placeholder="Masukkan asal instansi" value="{{ $vip->ket }}">
        </div>
  
        <div style="text-align: center;">
            <button type="submit" class="btn btn-warning" style="margin-right: 10px; color:#fff;">Submit</button>
            <button type="button" class="btn btn-secondary" style="color:#fff;" onclick="togglePopupedit('{{ $vip->id }}')">Close</button>
        </div>
    </form>
</div>
@endforeach

<!-- END POP UP Edit DATA-->


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
