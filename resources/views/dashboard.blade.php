@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<section class="mx-auto max-w-360 py-8 px-4 sm:px-8 md:px-10 lg:px-20">
  <div
    class="flex w-full flex-col items-center gap-4 bg-[#f9b800]
           rounded-[30px] sm:rounded-[30px] md:rounded-[30px] lg:rounded-[30px]
           px-4 py-4 sm:px-2 sm:py-3 md:px-4 md:py-3 xl:px-16 lg:flex-row lg:py-4"
  >
    <div class="text-center md:text-left lg:flex-1">
      <h2 class="mb-3 text-xl font-semibold underline leading-8.5 sm:text-[24px]">
        Hi, User
      </h2>

      <p class="text-base font-normal leading-7 text-[18px] md:text-[20px]">
        Explore innovative designs crafted with precision and elegance to enhance your lifestyle.
      </p>
    </div>

    <img
      class="order-2 w-full max-w-112.5 h-62.5 object-contain lg:order-0 lg:w-1/2"
      src="images/2.svg"
      alt=""
    />
  </div>
</section>

@endsection
