@extends('app')

@section('content')
<div class="card">
    <div class="card-body">
    <h4 class="font-weight-bold mb-0">Data Survey Kepuasan Pengguna</h4>
    <br>
<div class="d-flex justify-content-between align-items-center mt-3 mb-3">

 <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdownButton" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 20px;">
            Rekap
        </button>
        <ul class="dropdown-menu" aria-labelledby="exportDropdownButton">
            <li><a class="dropdown-item" href="{{ route('cetak-questions') }}" target="_blank" id="exportPdfButton"><i class="fas fa-file-pdf"></i> PDF</a></li>
            <li><a class="dropdown-item" href="{{ route('excel-questions') }}" id="exportExcelButton"><i class="fas fa-file-excel"></i> Excel</a></li>
        </ul>
    </div>
    <button class="btn btn-dark" type="button" style="padding: 5px 10px; color: #fff; margin-right: 10px;" onclick="togglePopup()">
            <i class="fas fa-plus"></i> &nbsp;Tambah Questions 
    </button>
</div>

<!-- SECTION CONTAINER TABEL -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-list">
                                <thead>
                                    <tr>
                                    <th>No.</th>
                                    <th>Questions</th>
                                    <th>Baik</th>
                                    <th>Sangat Baik</th>
                                    <th>Buruk</th>
                                    <th>Sangat Buruk</th>
                                    <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($surveys as $index => $survey)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $survey->questions }}</td>
                                        <td>{{ $survey->baik }}</td>
                                        <td>{{ $survey->sangat_baik }}</td>
                                        <td>{{ $survey->buruk }}</td>
                                        <td>{{ $survey->sangat_buruk }}</td>
                                        <td class="d-flex align-items-center">
                                            <button onclick="togglePopupedit({{ $survey->id }})" class="btn btn-success" style="color: white; padding: 5px 10px; height: auto;"> 
                                                <i class="fas fa-edit"></i>&nbsp;Edit
                                            </button>&nbsp;
                                            <form action="{{ route('survey.destroy', $survey->id) }}" method="POST" class="delete-form">
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
<!-- END SECTION CONTAINER TABEL -->


<!-- Pagination -->
<br></br>
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item {{ ($surveys->onFirstPage()) ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $surveys->previousPageUrl() }}">Previous</a>
        </li>
        @for ($i = 1; $i <= $surveys->lastPage(); $i++)
        <li class="page-item {{ ($surveys->currentPage() == $i) ? 'active' : '' }}">
            <a class="page-link" href="{{ $surveys->url($i) }}">{{ $i }}</a>
        </li>
        @endfor
        <li class="page-item {{ ($surveys->currentPage() == $surveys->lastPage()) ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $surveys->nextPageUrl() }}">Next</a>
        </li>
    </ul>
</nav>
<!-- End Pagination -->

<div id="popup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); max-width: 700px; ">
    <h4 style="margin-top: 0; margin-bottom: 20px; text-align: center;">Tambah Questions</h4>
    
    <form action="{{ route('survey.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Questions</label>
        <input type="text" class="form-control" id="questions" name="questions" placeholder="Masukkan Questions" required>
    </div>
        
        <div style="text-align: center;">
            <button type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
            <button type="button" class="btn btn-secondary" onclick="togglePopup()">Close</button>
        </div>
    </form>
</div>
<!-- END POP UP TAMBAH DATA -->

<!-- POP UP EDIT DATA -->
@foreach($surveys as $survey)
<div id="popupedit{{ $survey->id }}" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); max-width: 700px; z-index: 9999;">   <h4 style="margin-top: 0; margin-bottom: 20px; text-align: center;">Edit Data Tamu Kunjungan</h4>

    <form action="{{ route('survey.update', $survey->id) }}" method="POST">
    @csrf
    @method('PUT')
        <div class="form-group">
            <label for="questions">Questions</label>
            <input type="text" class="form-control" id="questions" name="questions" value="{{ $survey->name }}">
        </div>
        <div style="text-align: center;">
            <button type="submit" class="btn btn-primary" style="margin-right: 10px;">Update</button>
            <button type="button" class="btn btn-secondary" onclick="togglePopupedit('{{ $survey->id }}')">Close</button>
        </div>
    </form>
</div>
@endforeach
<!-- END POP UP EDIT DATA -->


<script>

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

</script>
@endsection
