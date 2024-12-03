<style>
    .dropdown-item {
        display: flex;
        align-items: center;
        padding: 10px;
    }
    
    .dropdown-item i {
        margin-right: 10px;
        color: #5e72e4;
    }

    .dropdown-item h6 {
        margin: 0;
        font-size: 14px;
        color: #32325d;
    }

    .dropdown-item p {
        margin: 0;
        font-size: 12px;
        color: #8898aa;
    }

    #newBookingCount {
        font-size: 10px;
        padding: 2px 5px;
        border-radius: 50%;
        position: relative;
        top: -10px;
        left: -10px;
        display: none;
    }

    .time-text {
        color: #8898aa;
        font-size: 12px;
        margin-left: 5px;
    }
    
    .notification-body {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .notification-icon {
        font-size: 20px;
        color: #5e72e4;
    }

    .dropdown-menu {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page">{{ str_replace('-', ' ', Request::path()) }}</li>
            </ol>
            <h6 class="font-weight-bolder mb-0 text-capitalize">{{ str_replace('-', ' ', Request::path()) }}</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar"> 
            <div class="ms-md-3 pe-md-3 d-flex align-items-center">
            </div>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link text-body font-weight-bold">
                        <span class="d-sm-inline d-none">{{$admins->nama_admin}}</span>
                        <i class="fa fa-user me-sm-1 px-2"></i>
                    </a>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0">
                        <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                    </a>
                </li>
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                        <span id="newBookingCount" class="badge bg-danger" style="display: none; position: relative; top: -10px; left: -10px;">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton" id="notificationDropdown">
                        <!-- Notifikasi dinamis akan diisi oleh JavaScript -->
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function timeAgo(date) {
        let now = new Date();
        let secondsPast = (now.getTime() - new Date(date).getTime()) / 1000;

        if (secondsPast < 60) {
            return `${Math.floor(secondsPast)} detik yang lalu`;
        }
        if (secondsPast < 3600) {
            return `${Math.floor(secondsPast / 60)} menit yang lalu`;
        }
        if (secondsPast < 86400) {
            return `${Math.floor(secondsPast / 3600)} jam yang lalu`;
        }
        if (secondsPast < 2592000) {
            return `${Math.floor(secondsPast / 86400)} hari yang lalu`;
        }
        if (secondsPast < 31536000) {
            return `${Math.floor(secondsPast / 2592000)} bulan yang lalu`;
        }
        return `${Math.floor(secondsPast / 31536000)} tahun yang lalu`;
    }

    $(document).ready(function () {
        function checkNewBookings() {
            $.ajax({
                url: '{{ route("admin.getNewBookings") }}',
                method: 'GET',
                success: function (response) {
                    let newBookings = response.new_bookings;
                    let notificationDropdown = $('#notificationDropdown');
                    
                    notificationDropdown.empty(); 

                    if (newBookings.length > 0) {
                        $('#newBookingCount').text(newBookings.length).show();
                        
                        newBookings.forEach(function (booking) {
                            notificationDropdown.append(`
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="my-auto notification-icon">
                                                <i class="fa-solid fa-circle-exclamation"></i>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center notification-body">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">${booking.nama_pembeli}</span> (Booking No: ${booking.nomor_booking})
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1"></i> ${booking.jam_booking}
                                                    <span class="time-text">| Updated: ${timeAgo(booking.updated_at)}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            `);
                        });
                    } else {
                        $('#newBookingCount').hide();
                        notificationDropdown.append(`
                            <li class="mb-2">
                                <a class="dropdown-item border-radius-md" href="javascript:;">
                                    <div class="d-flex py-1">
                                        <div class="my-auto">
                                            <i class="fa-solid fa-circle-exclamation"></i>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="text-sm font-weight-normal mb-1">
                                                Tidak ada booking baru
                                            </h6>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        `);
                    }
                },
                error: function () {
                    console.error('Gagal mengambil data booking terbaru.');
                }
            });
        }

        setInterval(checkNewBookings, 3000);
        checkNewBookings();
    });
</script>
