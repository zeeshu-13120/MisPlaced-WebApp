<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>@yield('title') Misplaced</title>
      @laravelPWA
      <style>
 #loading-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: #f8f4f4;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        #loading-gif {
            max-width: 100%;
            max-height: 100%;
        }
        </style>
      @include('Layout.styles')
      @yield('style')
   </head>
   <body>
    <div id="loading-container">
      <img id="loading-gif" src="{{asset('/images/LAUNCHER.gif')}}" alt="Loading...">
  </div>
      <header>
        <div class="bg-secondary">
          <div class="container py-1">
            <div class="main-menu d-none d-lg-block">
              <nav>
                 <ul id="navigation">
                    <li class="px-3"><a href="/">Home</a></li>
                    <li class="px-3"><a href="#about">About</a></li>
                    <li class="px-3"><a href="#contact">Contact</a></li>
                 </ul>
              </nav>
           </div>
           <div class="col-12">
            <div class="mobile_menu d-block d-lg-none"></div>
         </div>
          </div>
        </div>
         <div class="header-area  border-bottom bg-white">
            <div class="main-header">
               <div class="header-bottom header-sticky">
                  <div class="container py-1">
                     <div
                        class="d-flex align-items-center justify-content-between flex-wrap position-relative"
                        >
                        <div class="left-side d-flex align-items-center">
                           <div class="logo">
                              <a href="/"
                                 >
                                 <h2 class="text-primary"><img width="60px" src="{{asset("/images/logo.png")}}"/> <span class="text-dark">Mis</span>placed</h2>
                              </a>
                           </div>

                        </div>
                        <div class="f-right ml-15 ">
                           @if (Auth::User())
                           <div class="dropdown">
                              <div class="header-profile">
                                 <button class="btn py-2 dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <iconify-icon icon="bi:person-circle" class="me-2" ></iconify-icon>
                                    {{ Auth::User()->first_name }}
                                 </button>
                                 <ul class="dropdown-menu ">
                                    <li><a class="dropdown-item text-dark" href="{{url('my-posts')}}"><iconify-icon icon="fa6-solid:signs-post"></iconify-icon> My Post</a></li>
                                    <li><a class="dropdown-item text-dark" href="{{url('add-post')}}"><iconify-icon icon="gala:add"></iconify-icon> Add Post</a></li>
                                    <li><a class="dropdown-item text-dark" href="{{url('chat')}}"><iconify-icon icon="fluent:chat-16-filled"></iconify-icon> Chats</a></li>
                                    <li><a class="dropdown-item text-dark" href="{{url('profile_settings')}}"><iconify-icon icon="ant-design:setting-filled"></iconify-icon> Settings</a></li>
                                    <li><a class="dropdown-item text-dark" href="{{url('logout')}}"><iconify-icon icon="ic:baseline-logout"></iconify-icon> Logout</a></li>
                                 </ul>
                              </div>
                           </div>
                           @else
                           <a href="/login" class="btn_1 border-primary header-btn"
                              >Login </a>
                           @endif
                        </div>

                     </div>
                  </div>
               </div>
            </div>
         </div>
      </header>
      <main>
         @yield('content')
      </main>
      <footer>
         <div class="footer-wrapper bg-primary">
            <div class="footer-bottom-area">
               <div class="container">
                  <div class="footer-border">
                     <div class="row">
                        <div class="col-xl-12">
                           <div
                              class="footer-copy-right  d-flex justify-content-between flex-wrap"
                              >
                              <p class="text-white">
                                 Copyright &copy;
                                 <script>
                                    document.write(new Date().getFullYear());
                                 </script>
                                 All rights reserved | Misplaced
                              </p>
                              <div class="footer-social">
                                 <a href="#"
                                    ><i class="fab fa-facebook"></i
                                    ></a>
                                 <a href="#"><i class="fab fa-instagram"></i></a>
                                 <a href="#"><i class="fab fa-youtube"></i></a>
                                 <a href="#"><i class="fab fa-linkedin-in"></i></a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>
   </body>
   <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hide the loading container when the page is fully loaded
            var loadingContainer = document.getElementById("loading-container");
            loadingContainer.style.display = "none";
        });
    </script>
   @include('Layout.scripts')
   @yield('script')
   @if(session('error'))
   <script>
      toastr.error('{{ session('error') }}');
   </script>
   @endif
   @if(session('success'))
   <script>
      toastr.success('{{ session('success') }}');
   </script>
   @endif
   @if(session('error_msg'))
   <script>
      toastr.error('{{ session('error_msg') }}');
   </script>
   @endif
   @if(session('info'))
   <script>
      toastr.info('{{ session('info') }}');
   </script>
   @endif
   @if(session('warning'))
   <script>
      toastr.warning('{{ session('warning') }}');
   </script>
   @endif
</html>