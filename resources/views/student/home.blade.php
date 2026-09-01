@extends('student.layout')
@section('title') Home @endsection
@section('meta_description')
    منصة تعليمية منظمة لبناء مهارات حقيقية عبر كورسات ودروس واختبارات تفاعلية.
    An organized educational platform to build real skills through interactive courses, lessons, and tests.
@endsection
@section('style')
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--sp-6);
    }
    @media (max-width: 900px) { .courses-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 620px) { .courses-grid { grid-template-columns: 1fr; } }

    .section-head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: var(--sp-8); flex-wrap: wrap; gap: var(--sp-3); }
@endsection
@section('main')

    <header class="hero">
        <div class="wrap hero__inner">
            <div class="eyebrow mb-4">Learn at your own pace</div>
            <h1>Build real skills, one course at a time.</h1>
            <p class="lede mt-4" style="margin-left:auto;margin-right:auto;">
                Structured courses, clear lessons, and assessments that check what actually stuck — everything in one calm place.
            </p>
            <div class="hero__actions">
                <a href="{{ route('courses.index') }}" class="btn btn--primary">Browse courses</a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn--outline">Create free account</a>
                @endguest
            </div>

            @if(isset($courses) && $courses->count())
                <div class="stats-row">
                    <div class="stats-row__item">
                        <div class="stats-row__num">{{ $courses->count() }}+</div>
                        <div class="stats-row__lbl">Courses</div>
                    </div>
                    <div class="stats-row__item">
                        <div class="stats-row__num">{{ $courses->sum(fn($c) => $c->lessons->count()) }}+</div>
                        <div class="stats-row__lbl">Lessons</div>
                    </div>
                    <div class="stats-row__item">
                        <div class="stats-row__num">100%</div>
                        <div class="stats-row__lbl">Self-paced</div>
                    </div>
                </div>
            @endif
        </div>
    </header>

    @if(isset($courses) && $courses->count())
        <section class="section--tight">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <div class="eyebrow mb-2">Featured</div>
                        <h2>Start with one of these.</h2>
                    </div>
                    <a href="{{ route('courses.index') }}" class="btn btn--outline">View all courses →</a>
                </div>

                <div class="courses-grid">
                    @foreach($courses->take(6) as $course)
                        <a class="course-card" href="{{ route('courses.show', $course->slug) }}">
                            <div class="course-card__media">
                                @if($course->thumbnail_url)
                                    <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }} thumbnail">
                                @else
                                    <div class="course-card__no-thumb"><span>No cover</span></div>
                                @endif
                                @if($course->price > 0)
                                    <span class="course-card__price-tag">${{ $course->price }}</span>
                                @else
                                    <span class="course-card__price-tag is-free">Free</span>
                                @endif
                            </div>
                            <div class="course-card__body">
                                <div class="course-card__title">{{ $course->title }}</div>
                                <p class="course-card__desc">{{ $course->description }}</p>
                                <div class="course-card__meta">
                                    <div class="leader-row">
                                        <span class="leader-row__label">Access</span>
                                        <span class="leader-row__fill"></span>
                                        <span class="leader-row__value">{{ $course->access_duration_days ?? 'Lifetime' }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section--tight section--band">
        <div class="wrap" style="text-align:center;">
            <div class="eyebrow mb-3" style="justify-content:center;">Why {{ config('app.name', env('APP_NAME', 'this platform')) }}</div>
            <h2>Learning that respects your time.</h2>
            <div class="courses-grid mt-12" style="text-align:left;">
                <div class="card" style="padding: var(--sp-8);">
                    <i class="fa-solid fa-route" style="color:var(--color-brand);font-size:22px;"></i>
                    <h4 class="mt-4">Clear structure</h4>
                    <p class="text-muted mt-2" style="font-size:14.5px;">Every course is broken into lessons and chapters you can track from the first click.</p>
                </div>
                <div class="card" style="padding: var(--sp-8);">
                    <i class="fa-solid fa-pen-to-square" style="color:var(--color-secondary);font-size:22px;"></i>
                    <h4 class="mt-4">Real assessments</h4>
                    <p class="text-muted mt-2" style="font-size:14.5px;">Check your understanding with assessments built into the course, not bolted on after.</p>
                </div>
                <div class="card" style="padding: var(--sp-8);">
                    <i class="fa-solid fa-infinity" style="color:var(--color-brand);font-size:22px;"></i>
                    <h4 class="mt-4">Learn at your pace</h4>
                    <p class="text-muted mt-2" style="font-size:14.5px;">No deadlines, no pressure — pick up exactly where you left off, whenever you're ready.</p>
                </div>
            </div>
        </div>
    </section>

@endsection
