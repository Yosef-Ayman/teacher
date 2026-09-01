@extends('student.layout')
@section('title') About @endsection
@section('meta_description')
    تعرّف على رؤيتنا في تقديم منصة تعليمية هادئة وبسيطة تركز على العمق والتنظيم.
    Discover our vision of providing a calm and simple learning platform that focuses on depth and organization.
@endsection
@section('style')
    .values-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--sp-6); }
    @media (max-width: 900px) { .values-grid { grid-template-columns: 1fr; } }
@endsection
@section('main')

    <header class="hero" style="padding: var(--sp-16) 0;">
        <div class="wrap hero__inner">
            <div class="eyebrow mb-4">About us</div>
            <h1>We build calm places to learn.</h1>
            <p class="lede mt-4" style="margin-left:auto;margin-right:auto;">
                {{ config('app.name', env('APP_NAME', 'This platform')) }} started from a simple idea: learning shouldn't feel scattered across ten tools. One place for lessons, chapters and assessments — nothing more than you need.
            </p>
        </div>
    </header>

    <section class="section--tight">
        <div class="wrap">
            <div class="values-grid">
                <div class="card" style="padding: var(--sp-8);">
                    <span class="eyebrow mb-3">01</span>
                    <h4>Structure over noise</h4>
                    <p class="text-muted mt-3" style="font-size:14.5px;">Every course follows the same shape — lessons broken into chapters — so you always know how much is left and where to pick up.</p>
                </div>
                <div class="card" style="padding: var(--sp-8);">
                    <span class="eyebrow mb-3">02</span>
                    <h4>Honest pricing</h4>
                    <p class="text-muted mt-3" style="font-size:14.5px;">What you see on a course card is what you pay — no hidden fees, no subscriptions you forget to cancel.</p>
                </div>
                <div class="card" style="padding: var(--sp-8);">
                    <span class="eyebrow mb-3">03</span>
                    <h4>Built for depth</h4>
                    <p class="text-muted mt-3" style="font-size:14.5px;">Assessments are part of the course, not an afterthought — so finishing a course actually means something.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section--tight section--band">
        <div class="wrap" style="text-align:center;">
            <div class="eyebrow mb-3" style="justify-content:center;">Ready when you are</div>
            <h2>Pick a course and start today.</h2>
            <div class="hero__actions">
                <a href="{{ route('courses.index') }}" class="btn btn--primary">Browse courses</a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn--outline">Create free account</a>
                @endguest
            </div>
        </div>
    </section>

@endsection
