<!DOCTYPE html>

<!--
 // WEBSITE: https://themefisher.com
 // TWITTER: https://twitter.com/themefisher
 // FACEBOOK: https://www.facebook.com/themefisher
 // GITHUB: https://github.com/themefisher/
-->

<html lang="en-us">

<head>
    <meta charset="utf-8">
    <title>@yield('pageTitle')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">

    @yield('meta_tags')

    <link rel="shortcut icon" href="{{ blogInfo()->blog_favicon }}" type="image/x-icon">
    <link rel="icon" href="{{ blogInfo()->blog_favicon }}" type="image/x-icon">

    <!-- theme meta -->
    <meta name="theme-name" content="reporter" />

    <!-- # Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Neuton:wght@700&family=Work+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- # CSS Plugins -->
    <link rel="stylesheet" href="/front/plugins/bootstrap/bootstrap.min.css">

    <!-- # Main Style Sheet -->
    <link rel="stylesheet" href="/front/css/style.css">
</head>

<body>

    {{-- HEADER --}}
    @include('front.layouts.inc.header')

    <main>
        <section class="section">
            <div class="container">
                {{-- CONTENT --}}
                @yield('content')
                
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    @include('front.layouts.inc.footer')
    
    <script>
        window.addEventListener('showToaster', function(event){
        toastr.remove();
        if(event.detail.type === 'info'){
          toastr.info(event.detail.message);
        }else if(event.detail.type === 'success'){
          toastr.success(event.detail.message);
        }else if(event.detail.type === 'error'){
          toastr.error(event.detail.message);
        }else if(event.detail.type === 'warning'){
          toastr.warning(event.detail.message);
        } else {
          return false;
        }
      })
    </script>


    <!-- # JS Plugins -->
    <script src="/front/plugins/jquery/jquery.min.js"></script>
    <script src="/front/plugins/bootstrap/bootstrap.min.js"></script>

    <!-- Main Script -->
    <script src="/front/js/script.js"></script>

</body>

</html>
