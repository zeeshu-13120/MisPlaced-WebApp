@extends('Layout.layout')

@section('title','Home')

@section('content')




    <div class="slider-area">
      <div class="slider-active">
        <div
          class="single-slider slider-height slider-bg1 d-flex align-items-center"
        >
          <div class="container position-relative">
            <div class="row">
              <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-10">
                <div class="hero__caption text-center" >
                  <h1 class="text-center">
                    <span>Misplaced</span
                    ><br />
                    Your trusted partner in finding what's lost.
                  </h1>
                  <a href="/signup" class="text-white mx-auto btn btn-primary"
                  >Join Now </a>
            </div>

              </div>
              <div class="col-lg-5">
                <div
                  class="hero-man d-none d-lg-block f-right"
                  data-animation="bounceIn"
                  data-delay=".4s"
                  style="animation-delay: 0.4s"
                >
                  <img class="w-75 ms-auto" src={{asset('img/hero/hero-man.png')}} alt />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <section id="about" class="our-services section-padding section-bg1 fix">
      <div class="container">
        <div class="row position-relative">
          <div class="col-xxl-3 col-xl-3">
            <div class="help-img"></div>
          </div>
          <div
            class="offset-lg-3 col-xxl-9 col-xl-9 col-lg-9 col-md-12 col-sm-12"
          >
            <div class="row">
              <div
                class="offset-xl-5 offset-lg-5 offset-md-5 col-xl-6 col-lg-7 col-md-7"
              >
                <div class="section-tittle mb-70 services-padding">
                  <h2>
                    We help people in finding
                    there lost valuable items
                  </h2>
                  <p>
                    Discover the ultimate solution for lost items! Misplaced - Your trusted partner in finding what's lost. Reclaim your belongings effortlessly with us today!
                  </p>
                </div>
              </div>
            </div>
            <div class="services-active dot-style">
              <div class="single-services mb-30 text-center">

                <div class="services-cap">
                  <p>
                    Misplaced saved my day! Found my lost keys within minutes. Simple and effective – a lifesaver for forgetful folks!
                  </p>
                  <h4 class="text-center border-top pt-4">Zeeshan</h4>
                </div>
              </div>

              <div class="single-services mb-30 text-center">

                <div class="services-cap">
                  <p>
                    Great app! Helped me locate my missing phone fast. A must-have tool for anyone prone to losing things.
                  </p>
                  <h4 class="text-center border-top pt-4">Raja</h4>

                </div>
              </div>

              <div class="single-services mb-30 text-center">

                <div class="services-cap">
                  <p>
                    Misplaced is a game-changer! Recovered my lost wallet hassle-free. Super user-friendly and incredibly useful.
                  </p>
                  <h4 class="text-center border-top pt-4">shahzaib</h4>

                </div>
              </div>

              <div class="single-services mb-30 text-center">

                <div class="services-cap">
                  <p>
                    I love Misplaced! It helped me find my lost laptop. Quick and efficient service. Highly recommended for anyone forgetful
                  </p>
                  <h4 class="text-center border-top pt-4">Haris</h4>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="contact" class="bg-white  section-padding section-bg1 fix ">
        <div class="row container mx-auto">
            <div class="col-12">
            <h2 class="contact-title">Get in Touch</h2>
            </div>
            <div class="col-lg-8">
              <form class="form-contact contact_form" action="{{ route('contact.submit') }}" method="post" id="contactForm" novalidate="novalidate">
                @csrf            <div class="row">
            <div class="col-12">
            <div class="form-group">
            <textarea required class="form-control w-100" name="message" id="message" cols="30" rows="9" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Message'" placeholder=" Enter Message"></textarea>
            </div>
            </div>
            <div class="col-sm-6">
            <div class="form-group">
            <input required class="form-control valid" name="name" id="name" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'" placeholder="Enter your name">
            </div>
            </div>
            <div class="col-sm-6">
            <div class="form-group">
            <input required class="form-control valid" name="email" id="email" type="email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter email address'" placeholder="Email">
            </div>
            </div>
            <div class="col-12">
            <div class="form-group">
            <input required class="form-control" name="subject" id="subject" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Subject'" placeholder="Enter Subject">
            </div>
            </div>
            </div>
            <div class="form-group mt-3">
            <button type="submit" class="button button-contactForm boxed-btn">Send</button>
            </div>
            </form>
            </div>
            <div class="col-lg-3 offset-lg-1">
            <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-home"></i></span>
            <div class="media-body">
            <h3>AECHS,Rawalpindi,Pakistan.</h3>
            <p>Gulzare quaid</p>
            </div>
            </div>
            <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-tablet"></i></span>
            <div class="media-body">
            <h3>+923445444396</h3>
            <p>Mon to Sun 24/7</p>
            </div>
            </div>
            <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-email"></i></span>
            <div class="media-body">
            <h3>support@misplaced.com</h3>
            <p>Send us your query anytime!</p>
            </div>
            </div>
            </div>
            </div>
    </section>

    <section class="wantToWork-area">
      <div class="container">
        <div class="wants-wrapper w-padding">
          <div class="row align-items-center justify-content-between">
            <div class="col-xl-8 col-lg-9 col-md-8">
              <div class="wantToWork-caption">
                <h2>Find Your Valuable Items back.</h2>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-4">
              <button href="/signup" style="border:1px solid white" class="text-white btn   f-right"
                >Create new account</button
              >
            </div>
          </div>
        </div>
      </div>
    </section>



  <div id="back-top">
    <iconify-icon icon="icon-park-solid:up-two"></iconify-icon>
  </div>


@endsection