@extends('layouts.user_type.auth')

@section('content')

<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-header text-right">
                <a href="{{ route('pelayanan.create') }}" class="btn btn-info" style="background-color: {{ $profile->warna }}; float: right; color: white;" role="button">Tambah Pelayanan</a>
            </div>
            <div class="card-body">          

              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr style="text-align: center;">
                    <th scope="col">No</th>
                    <th>Nama Karyawan</th>
                    <th>No. Pelayanan</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                @php
                    $no = 1;
                @endphp
                  @foreach($pelayanan as $pel)
                  <tr style="text-align: center;">
                    <th scope="row">{{ $no++ }}</th>
                    <td>{{ $pel->nama_admin }}</td>
                    <td>{{ $pel->jenis }} {{ $pel->no_pelayanan }}</td>
                    <td>
                    <form action="{{ route('pelayanan.edit', $pel->id_pelayanan) }}" method="GET" style="display: inline;">
                        <button type="submit" style="background-color: white; border: 2px solid {{ $profile->warna }}; border-radius: 15%; color: {{ $profile->warna }};">
                            <i class="fas fa-pen-to-square"></i>
                        </button>
                    </form>
                    <form action="{{ route('pelayanan.destroy', $pel->id_pelayanan) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah anda yakin ingin menghapus pelayanan?')" class="btn-hapus" style="background-color: {{ $profile->warna }}; border: 2px solid {{ $profile->warna }}; border-radius: 15%;">
                            <i class="fas fa-trash-can" style="color: white;"></i>
                        </button>
                    </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- SweetAlert2 for Success Message -->
    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                timer: 3000
            });
        </script>
    @endif
@endsection

@push('scripts')
<script>
    $(document).on('click', '.btn-hapus', function(e) {
        e.preventDefault();

        const form = $(this).closest('form'); // Mengambil form terdekat

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus Saja!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Submit form jika pengguna mengkonfirmasi
            }
        });
    });
</script>
@endpush
