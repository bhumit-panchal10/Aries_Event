@extends('layouts.front')
@section('title', 'About Us')
@section('content')

    @include('common.alert')

    <!-- ================================
             BRAND STORY & FOUNDERS' VISION
        ================================ -->
    <section class="py-5 bg-light" id="brand-story">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-md-6" data-aos="fade-right">
                    <img src="{{ asset('assets/front/images/about/founders.jpg') }}" class="img-fluid rounded shadow"
                        alt="Oroveda Founders">
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <h2 class="section-title aos-init aos-animate" data-aos="fade-up">Our Brand Story & Founders’ Vision
                    </h2>

                    <p class="text-muted">
                        Oroveda was born from a desire to bring back the <strong>ancient Indian wisdom</strong> of
                        nourishment through pure ingredients.
                        Our founders, inspired by their ancestral traditions, envisioned a world where <span
                            class="text-warning fw-semibold">every home could enjoy ghee</span> crafted the way nature
                        intended —
                        slow, sacred, and sustainable.
                    </p>
                    <p class="text-muted">
                        What started as a small family venture has grown into a movement celebrating the <em>Gir cow</em> —
                        an emblem of purity and health.
                        At Oroveda, we blend time-honored techniques with ethical farming to create ghee that heals the body
                        and uplifts the spirit.
                    </p>
                    <blockquote class="blockquote border-start border-warning ps-3 mt-4">
                        <p class="mb-0">“We don’t just make ghee — we preserve a legacy.”</p>
                        <footer class="blockquote-footer mt-2">The Oroveda Founders</footer>
                    </blockquote>
                </div>
            </div>
        </div>
    </section>
    <!-- ================================
                  JOURNEY OF OROVEDA
        ================================ -->
    <section id="journey" class="py-5">
        <div class="container">

            <h2 class="section-title aos-init aos-animate" data-aos="fade-up">The Journey of <span
                    class="text-warning">Oroveda</span> </h2>
            <div class="timeline">
                <!-- 2018 -->
                <div class="timeline-item" data-aos="fade-right">
                    <div class="timeline-content">
                        <h5 class="fw-semibold text-warning">2018 — The Beginning</h5>
                        <p class="text-muted small mb-0">
                            Our founders began their journey in rural Gujarat, inspired by their ancestors’ traditional
                            methods of making ghee using the <em>Bilona process </em>. Oroveda was born out of a passion to
                            restore purity and authenticity to modern food.
                        </p>
                    </div>
                </div>

                <!-- 2019 -->
                <div class="timeline-item" data-aos="fade-left">
                    <div class="timeline-content">
                        <h5 class="fw-semibold text-warning">2019 — The First Batch</h5>
                        <p class="text-muted small mb-0">
                            With just a handful of Gir cows and a small farm, the first jars of Oroveda Ghee were
                            handcrafted and distributed locally. The overwhelming love from our customers fueled our
                            mission.
                        </p>
                    </div>
                </div>

                <!-- 2020 -->
                <div class="timeline-item" data-aos="fade-right">
                    <div class="timeline-content">
                        <h5 class="fw-semibold text-warning">2020 — Certified Organic</h5>
                        <p class="text-muted small mb-0">
                            Oroveda achieved <strong>organic certification</strong> for its ghee production and
                            farming practices — ensuring chemical-free, cruelty-free, and environment-friendly standards.
                        </p>
                    </div>
                </div>

                <!-- 2022 -->
                <div class="timeline-item" data-aos="fade-left">
                    <div class="timeline-content">
                        <h5 class="fw-semibold text-warning">2022 — National Expansion</h5>
                        <p class="text-muted small mb-0">
                            Oroveda products reached stores and homes across India. The brand gained recognition for
                            its commitment to <strong>authenticity, wellness, and sustainability</strong>.
                        </p>
                    </div>
                </div>

                <!-- 2025 -->
                <div class="timeline-item" data-aos="fade-right">
                    <div class="timeline-content">
                        <h5 class="fw-semibold text-warning">2025 — Global Presence</h5>
                        <p class="text-muted small mb-0">
                            Oroveda has expanded to international markets, representing India’s traditional purity on a
                            global stage. Our mission continues — to deliver ghee that’s pure, ethical, and full of life.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================
                 OROVEDA PHILOSOPHY
        ================================ -->
    <section class="py-5" id="philosophy">
        <div class="container text-center">
            <h2 class="fw-bold mb-5" data-aos="fade-up">The Oroveda Philosophy</h2>
            <div class="row g-4">
                <!-- Purity -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="mb-3"><i class="bi bi-droplet-half text-warning fs-1"></i></div>
                            <h5 class="fw-semibold mb-2">Purity</h5>
                            <p class="text-muted small">
                                We source milk only from <strong>indigenous Gir cows</strong> and follow natural grazing
                                methods.
                                No preservatives, no shortcuts — only pure, golden ghee made from love and integrity.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Tradition -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="mb-3"><i class="bi bi-mortarboard-fill text-warning fs-1"></i></div>
                            <h5 class="fw-semibold mb-2">Tradition</h5>
                            <p class="text-muted small">
                                Our <em>Bilona process</em> is inspired by Ayurveda — churning curd with a wooden hand whisk
                                to retain nutrients
                                and energy, exactly as done for centuries in Indian households.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Sustainability -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="mb-3"><i class="bi bi-leaf-fill text-warning fs-1"></i></div>
                            <h5 class="fw-semibold mb-2">Sustainability</h5>
                            <p class="text-muted small">
                                From ethical dairy practices to eco-friendly packaging, Oroveda strives to create wellness
                                for
                                both people and planet. We ensure <strong>zero waste and mindful sourcing</strong> every
                                step of the way.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================
                    PROCESS SNAPSHOTS
        ================================ -->
    <section class="py-5 bg-light" id="process-snapshots">
        <div class="container">
            <h2 class="fw-bold text-center mb-5" data-aos="fade-up">Our Process: From Farm to Jar</h2>
            <div class="row g-4 text-center">
                <!-- Step 1 -->
                <div class="col-md-3 col-sm-6" data-aos="zoom-in" data-aos-delay="100">
                    <div class="card border-0 h-100">
                        <img src="{{ asset('assets/front/images/process/milking.jpg') }}"
                            class="card-img-top rounded shadow-sm" alt="Milking Gir Cows">
                        <div class="card-body">
                            <h6 class="fw-semibold text-warning">Step 1</h6>
                            <p class="small text-muted">Fresh A2 milk is collected from happy, grass-fed Gir cows.</p>
                        </div>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="col-md-3 col-sm-6" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card border-0 h-100">
                        <img src="{{ asset('assets/front/images/process/curd.jpg') }}"
                            class="card-img-top rounded shadow-sm" alt="Curd Formation">
                        <div class="card-body">
                            <h6 class="fw-semibold text-warning">Step 2</h6>
                            <p class="small text-muted">The milk is set into curd overnight to preserve enzymes and
                                probiotics.</p>
                        </div>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="col-md-3 col-sm-6" data-aos="zoom-in" data-aos-delay="300">
                    <div class="card border-0 h-100">
                        <img src="{{ asset('assets/front/images/process/churning.jpg') }}"
                            class="card-img-top rounded shadow-sm" alt="Bilona Churning">
                        <div class="card-body">
                            <h6 class="fw-semibold text-warning">Step 3</h6>
                            <p class="small text-muted">The curd is hand-churned using the traditional wooden
                                <em>Bilona</em>.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Step 4 -->
                <div class="col-md-3 col-sm-6" data-aos="zoom-in" data-aos-delay="400">
                    <div class="card border-0 h-100">
                        <img src="{{ asset('assets/front/images/process/simmering.jpg') }}"
                            class="card-img-top rounded shadow-sm" alt="Slow Simmering">
                        <div class="card-body">
                            <h6 class="fw-semibold text-warning">Step 4</h6>
                            <p class="small text-muted">Butter is gently simmered to produce golden, aromatic Oroveda Ghee.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
