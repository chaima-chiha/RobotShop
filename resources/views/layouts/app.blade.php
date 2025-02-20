
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>RobotShop Academy</title>

        <!-- mobile responsive meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!-- ** Plugins Needed for the Project ** -->
        <!-- Bootstrap -->
        <link rel="stylesheet" href="{{ asset('template/plugins/bootstrap/bootstrap.min.css') }}">
        <!-- slick slider -->
        <link rel="stylesheet" href="{{ asset('template/plugins/slick/slick.css') }}">
        <!-- themefy-icon -->
        <link rel="stylesheet" href="{{ asset('template/plugins/themify-icons/themify-icons.css') }}">
        <!-- animation css -->
        <link rel="stylesheet" href="{{ asset('template/plugins/animate/animate.css') }}">
        <!-- aos -->
        <link rel="stylesheet" href="{{ asset('template/plugins/aos/aos.css') }}">
        <!-- venobox popup -->
        <link rel="stylesheet" href="{{ asset('template/plugins/venobox/venobox.css') }}">

        <!-- Main Stylesheet -->
        <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">

        <!--Favicon-->
        <link rel="shortcut icon" href="{{ asset('template/images/favicon.ico') }}" type="image/x-icon">
        <link rel="icon" href="{{ asset('template/images/favicon.ico') }}" type="image/x-icon">

      </head>
      <body>
        @include('partials.topHeader') <!-- Inclure le topHeader  -->
        @include('partials.navbar')  <!-- Inclure la navbar  -->

        <div class="container mt-4">
            @yield('content')
        </div>

        @include('partials.footer')  <!-- Inclure le footer  -->
        <!-- jQuery -->
<script src="{{ asset('template/plugins/jQuery/jquery.min.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('template/plugins/bootstrap/bootstrap.min.js') }}"></script>
<!-- slick slider -->
<script src="{{ asset('template/plugins/slick/slick.min.js') }}"></script>
<!-- aos -->
<script src="{{ asset('template/plugins/aos/aos.js') }}"></script>
<!-- venobox popup -->
<script src="{{ asset('template/plugins/venobox/venobox.min.js') }}"></script>
<!-- mixitup filter -->
<script src="{{ asset('template/plugins/mixitup/mixitup.min.js') }}"></script>
<!-- google map -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcABaamniA6OL5YvYSpB3pFMNrXwXnLwU&libraries=places"></script>
<script src="plugins/google-map/gmap.js"></script>

<!-- Main Script -->
<script src="{{ asset('template/js/script.js') }}"></script>
<!-- axios ajouter -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


    </body>
</html>
