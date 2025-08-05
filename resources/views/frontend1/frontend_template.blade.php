<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="E-PERPUSDES Widya Sastra" />
        <title>E-PERPUSDES Widya Sastra</title>
        @include('frontend1.style')
    </head>
    <body id="page-top">
        <!-- Navigation-->
        @include('frontend1.navigation')
        <!-- Masthead-->
        <header class="masthead">
            @livewire('frontend.hero-carousel')
        </header>

        <!-- Books Section-->
        @include('frontend1.buku')


        <!-- Informasi Section-->
        @include('frontend1.informasi')


        <!-- Footer-->
        @include('frontend1.footer')
        <!-- Copyright Section-->
        <div class="copyright py-4 text-center text-white">
            <div class="container"><small>Copyright &copy; Perpusdes Widya Sastra 2025</small></div>
        </div>

        @include('frontend1.script')
    </body>
</html>
