<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BorrowMe')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<style>
    .poppins-thin {
    font-family: "Poppins", sans-serif;
    font-weight: 100;
    font-style: normal;
    }

    .poppins-extralight {
    font-family: "Poppins", sans-serif;
    font-weight: 200;
    font-style: normal;
    }

    .poppins-light {
    font-family: "Poppins", sans-serif;
    font-weight: 300;
    font-style: normal;
    }

    .poppins-regular {
    font-family: "Poppins", sans-serif;
    font-weight: 400;
    font-style: normal;
    }

    .poppins-medium {
    font-family: "Poppins", sans-serif;
    font-weight: 500;
    font-style: normal;
    }

    .poppins-semibold {
    font-family: "Poppins", sans-serif;
    font-weight: 600;
    font-style: normal;
    }

    .poppins-bold {
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: normal;
    }

    .poppins-extrabold {
    font-family: "Poppins", sans-serif;
    font-weight: 800;
    font-style: normal;
    }

    .poppins-black {
    font-family: "Poppins", sans-serif;
    font-weight: 900;
    font-style: normal;
    }

    .poppins-thin-italic {
    font-family: "Poppins", sans-serif;
    font-weight: 100;
    font-style: italic;
    }

    .poppins-extralight-italic {
    font-family: "Poppins", sans-serif;
    font-weight: 200;
    font-style: italic;
    }

    .poppins-light-italic {
    font-family: "Poppins", sans-serif;
    font-weight: 300;
    font-style: italic;
    }

    .poppins-regular-italic {
    font-family: "Poppins", sans-serif;
    font-weight: 400;
    font-style: italic;
    }

    .poppins-medium-italic {
    font-family: "Poppins", sans-serif;
    font-weight: 500;
    font-style: italic;
    }

    .poppins-semibold-italic {
    font-family: "Poppins", sans-serif;
    font-weight: 600;
    font-style: italic;
    }

    .poppins-bold-italic {
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-style: italic;
    }

    .poppins-extrabold-italic {
    font-family: "Poppins", sans-serif;
    font-weight: 800;
    font-style: italic;
    }

    .poppins-black-italic {
    font-family: "Poppins", sans-serif;
    font-weight: 900;
    font-style: italic;
    }
</style>
<body style="background-color: #F9F8F6;" class="poppins-regular">
    @if (Auth::user() && !request()->routeIs('login.page', 'register.page', 'landing'))
        <x-navbar />
       <x-sidebar />
    @endif

    <x-loading fullscreen text="Loading..." />

    <!-- Flash message modal (success / error) -->
    <x-alert-modal />

    @if (Auth::user() && !request()->routeIs('login.page', 'register.page', 'landing'))
        <div style="margin-left: 260px;">
            @yield('content')
        </div>
    @else
        <div>
            @yield('content')
        </div>
    @endif

<script>
    function showLoading() {
        document.getElementById('loading').classList.remove('d-none');
        document.getElementById('loading').classList.add('d-flex');
    }

    // Debug and fix dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Bootstrap version:', typeof bootstrap !== 'undefined' ? bootstrap.Dropdown.VERSION : 'Bootstrap not loaded');
        
        // Ensure all dropdowns are initialized
        const dropdownElementList = document.querySelectorAll('[data-bs-toggle="dropdown"]');
        console.log('Found dropdowns:', dropdownElementList.length);
        
        if (typeof bootstrap !== 'undefined') {
            dropdownElementList.forEach(function(dropdownToggleEl) {
                try {
                    new bootstrap.Dropdown(dropdownToggleEl);
                    console.log('Dropdown initialized successfully');
                } catch (e) {
                    console.error('Error initializing dropdown:', e);
                }
            });
        } else {
            console.error('Bootstrap is not loaded!');
        }
    });
</script>

<!-- Bootstrap JS at the end for proper loading -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
