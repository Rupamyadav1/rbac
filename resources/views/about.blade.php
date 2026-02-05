@extends('layouts.main')

@section('content')

<div class="bg-white">
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 py-16 text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">About Fleek Media</h1>
        <p class="text-gray-600 max-w-3xl mx-auto">
            Fleek Media is a modern digital platform dedicated to building clean, creative, and user‑focused digital experiences.
        </p>
    </section>

```
<!-- About Content -->
<section class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-2 gap-12 items-center">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Who We Are</h2>
        <p class="text-gray-600 mb-4">
            At Fleek Media, we believe great design and solid technology go hand in hand. We focus on crafting digital solutions that are visually appealing, scalable, and easy to use.
        </p>
        <p class="text-gray-600">
            Our team is driven by creativity, innovation, and a passion for delivering meaningful digital products that help businesses grow online.
        </p>
    </div>
    <div class="flex justify-center">
        <img src="https://illustrations.popsy.co/gray/web-design.svg" alt="About Fleek Media" class="w-80">
    </div>
</section>

<!-- Mission & Vision -->
<section class="bg-gray-100 py-14">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-10">
        <div class="bg-white p-8 rounded-lg shadow">
            <h3 class="text-xl font-semibold mb-3">Our Mission</h3>
            <p class="text-gray-600">
                To create reliable, modern, and high‑quality digital solutions that empower brands and businesses to succeed in the digital world.
            </p>
        </div>
        <div class="bg-white p-8 rounded-lg shadow">
            <h3 class="text-xl font-semibold mb-3">Our Vision</h3>
            <p class="text-gray-600">
                To become a trusted digital partner known for innovation, quality, and user‑centric design.
            </p>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="max-w-7xl mx-auto px-6 py-16">
    <h2 class="text-2xl font-semibold text-center mb-10">Why Choose Fleek Media</h2>
    <div class="grid md:grid-cols-4 gap-6 text-center">
        <div class="p-6 bg-white rounded-lg shadow">
            <h4 class="font-semibold mb-2">User‑Focused</h4>
            <p class="text-gray-600 text-sm">We prioritize user experience in everything we build.</p>
        </div>
        <div class="p-6 bg-white rounded-lg shadow">
            <h4 class="font-semibold mb-2">Modern Design</h4>
            <p class="text-gray-600 text-sm">Clean, responsive, and visually appealing interfaces.</p>
        </div>
        <div class="p-6 bg-white rounded-lg shadow">
            <h4 class="font-semibold mb-2">Scalable Solutions</h4>
            <p class="text-gray-600 text-sm">Built to grow with your business.</p>
        </div>
        <div class="p-6 bg-white rounded-lg shadow">
            <h4 class="font-semibold mb-2">Reliable Support</h4>
            <p class="text-gray-600 text-sm">We believe in long‑term partnerships.</p>
        </div>
    </div>
</section>


```

</div>
@endsection
