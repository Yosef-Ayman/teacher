@extends('student.layout')
@section('title') {{ $course->title }} @endsection
@section('meta_description') {{ $course->description }} @endsection
@section('meta_image') {{ $course->thumbnail_url }} @endsection
@section('style')
    .course-hero { padding: var(--sp-16) 0 var(--sp-8); }
    .course-hero__grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: var(--sp-12);
        align-items: start;
    }
    @media (max-width: 900px) { .course-hero__grid { grid-template-columns: 1fr; } }

    .buy-card {
        background: var(--color-surface-raised);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-card);
        position: sticky;
        top: 96px;
    }
    .buy-card__media { aspect-ratio: 3/2; }
    .buy-card__media img { width:100%; height:100%; object-fit:cover; }
    .buy-card__body { padding: var(--sp-6); }
    .buy-card__price { font-family: var(--font-display); font-size: 34px; font-weight: 800; }

    .body-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: var(--sp-12);
        align-items: start;
    }
    @media (max-width: 900px) { .body-grid { grid-template-columns: 1fr; } }

    .lesson-block { border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--sp-6); margin-bottom: var(--sp-4); background: var(--color-surface-raised); }
    .lesson-block__head { display: flex; gap: var(--sp-4); align-items: flex-start; }
    .lesson-block__no {
        font-family: var(--font-mono);
        font-weight: 700;
        font-size: 13px;
        color: var(--color-brand);
        width: 32px; height: 32px;
        border-radius: 50%;
        background: var(--color-brand-tint);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .lesson-block__chapters { margin-top: var(--sp-3); display:flex; flex-direction:column; gap: var(--sp-1); }
    .chapter-row {
        display: flex;
        align-items: center;
        gap: var(--sp-3);
        padding: var(--sp-2) var(--sp-3);
        border-radius: var(--radius-sm);
        font-size: 13.5px;
        color: var(--color-muted);
    }
    .chapter-row:hover { background: var(--color-brand-tint); color: var(--color-brand-strong); }
    .chapter-row__icon {
        width: 22px; height: 22px;
        border-radius: 50%;
        display:flex; align-items:center; justify-content:center;
        background: var(--color-secondary-tint);
        color: var(--color-secondary);
        flex-shrink: 0;
        font-size: 10px;
    }
    .chapter-row__no { font-family: var(--font-mono); font-size: 11px; color: var(--color-faint); width: 16px; }
@endsection
@section('main')
    @session('toast')
        @switch($value['type'])
            @case('success')
                <div class="wrap"><div class="alert alert-success">{{ $value['message'] }}</div></div>
                @break
            @case('info')
                <div class="wrap"><div class="alert alert-info">{{ $value['message'] }}</div></div>
                @break
            @case('error')
                <div class="wrap"><div class="alert alert-danger">{{ $value['message'] }}</div></div>
                @break
        @endswitch
    @endsession
    <header class="course-hero">
        <div class="wrap">
            <div class="course-hero__grid">
                <div>
                    @if($enrollment)
                        <div class="flex items-center gap-3 mb-4">
                            <span class="badge badge--secondary">
                                <span class="badge__dot"></span> Enrolled
                            </span>
                        </div>
                    @endif
                    <div class="eyebrow mb-3">Course</div>
                    <h1>{{ $course->title }}</h1>
                    <p class="lede mt-4">{{ $course->description }}</p>
                    <div class="mt-8">
                        <div class="leader-row">
                            <span class="leader-row__label">Price</span>
                            <span class="leader-row__fill"></span>
                            <span class="leader-row__value">{{ $course->price ? '$' . $course->price : 'Free' }}</span>
                        </div>
                        <div class="leader-row">
                            <span class="leader-row__label">Access duration</span>
                            <span class="leader-row__fill"></span>
                            <span class="leader-row__value">{{ $course->access_duration_days ?? 'Lifetime' }}</span>
                        </div>
                        <div class="leader-row">
                            <span class="leader-row__label">Publishes</span>
                            <span class="leader-row__fill"></span>
                            <span class="leader-row__value">{{ \Carbon\Carbon::parse($course->publish_at)->tz('Africa/Cairo')->format('d - D - Y | h:i A') . ' UTC' }}</span>
                        </div>
                        <div class="leader-row">
                            <span class="leader-row__label">Slug</span>
                            <span class="leader-row__fill"></span>
                            <span class="leader-row__value">{{ $course->slug }}</span>
                        </div>
                    </div>
                </div>
                <aside class="buy-card">
                    @if($course->thumbnail_url)
                    <div class="buy-card__media">
                        <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }} thumbnail">
                    </div>
                    @else
                    <div class="course-card__media">
                        <div class="course-card__no-thumb"><span>No cover</span></div>
                    </div>
                    @endif
                    <div class="buy-card__body">
                        <div class="buy-card__price mb-4">{{ $course->price ? '$' . $course->price : 'Free' }}</div>
                        @auth
                            @if(! $enrollment)
                                <form action="{{ route('courses.buy', ['slug' => $course->slug]) }}" method="post" onsubmit="return confirm('Are you sure you want to buy this course?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn--block">Buy Now</button>
                                </form>
                            @else
                                <a href="{{ route('lessons.index', ['slug' => $course->slug]) }}" class="btn btn--secondary btn--block">Resume Course →</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn--outline btn--block">Login First</a>
                        @endauth
                        <div class="mt-3">
                            <button type="button" id="shareBtn" onclick="copyCourseLink()" class="btn btn--outline btn--block" style="gap: var(--sp-2);">
                                <i class="fa-solid fa-share-nodes" id="shareIcon"></i>
                                <span id="shareText">Share Course</span>
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </header>

    <section class="section--tight">
        <div class="wrap body-grid">
            <div>
                <div class="eyebrow mb-4">Table of contents</div>

                @php $lessonCount = 1; @endphp
                @foreach($course->lessons as $lesson)
                <div class="lesson-block">
                    <div class="lesson-block__head">
                        <span class="lesson-block__no">{{ $lessonCount++ }}</span>
                        <div style="flex:1;">
                            <h4>{{ $lesson->title }}</h4>
                        </div>
                    </div>
                    <div class="lesson-block__chapters">
                        @php $chapterCount = 1; @endphp
                        @foreach($lesson->chapters as $chapter)
                        <div class="chapter-row">
                            <span class="chapter-row__no">{{ $chapterCount++ }}</span>
                            <span class="chapter-row__icon">
                                @switch($chapter->type)
                                    @case('markdown')
                                        <i class="fa-solid fa-book-open"></i>
                                        @break
                                    @case('video')
                                        <i class="fa-solid fa-play"></i>
                                        @break
                                    @case('assessment')
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        @break
                                @endswitch
                            </span>
                            <span>{{ $chapter->title }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            @if($enrollment)
                <aside>
                    <div class="card" style="padding: var(--sp-6);">
                        @if($enrollment->expires_at)
                            @php
                                $start = \Carbon\Carbon::parse($enrollment->created_at);
                                $end = \Carbon\Carbon::parse($enrollment->expires_at);
                                $now = \Carbon\Carbon::now();
                                $total = max($start->diffInSeconds($end), 1);
                                $elapsed = $start->diffInSeconds($now);
                                $percent = min(max(($elapsed / $total) * 100, 0), 100);
                            @endphp
                            <div class="eyebrow mb-3">Your access</div>
                            <div class="progress mb-4"><div class="progress__fill" style="width:{{ $percent }}%"></div></div>
                        @endif
                        <div class="leader-row">
                            <span class="leader-row__label">Started</span>
                            <span class="leader-row__fill"></span>
                            <span class="leader-row__value">{{ \Carbon\Carbon::parse($enrollment->created_at)->tz('Africa/Cairo')->format('M d, Y') }}</span>
                        </div>
                        <div class="leader-row">
                            <span class="leader-row__label">Expires</span>
                            <span class="leader-row__fill"></span>
                            <span class="leader-row__value">{{ $enrollment->expires_at ? \Carbon\Carbon::parse($enrollment->expires_at)->tz('Africa/Cairo')->format('M d, Y') : 'Lifetime' }}</span>
                        </div>
                        <a href="{{ route('lessons.index', ['slug' => $course->slug]) }}" class="btn btn--primary btn--block mt-4">Resume →</a>
                    </div>
                </aside>
            @endif
        </div>
    </section>
@endsection
@section('script')
    function copyCourseLink() {
        const url = window.location.href;
        const shareBtn = document.getElementById('shareBtn');
        const shareIcon = document.getElementById('shareIcon');
        const shareText = document.getElementById('shareText');

        if (navigator.share) {
            navigator.share({
                title: "{{ $course->title }}",
                text: "{{ $course->description }}",
                url: url,
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(url).then(() => {
                shareIcon.className = "fa-solid fa-check";
                shareIcon.style.color = "var(--color-success)";
                shareText.innerText = "Link Copied!";
                shareBtn.style.borderColor = "var(--color-success)";

                setTimeout(() => {
                    shareIcon.className = "fa-solid fa-share-nodes";
                    shareIcon.style.color = "";
                    shareText.innerText = "Share Course";
                    shareBtn.style.borderColor = "";
                }, 2000);
            });
        }
    }
@endsection
