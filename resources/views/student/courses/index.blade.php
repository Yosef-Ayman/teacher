@extends('student.layout')
@section('title') Courses @endsection
@section('meta_description')
    تصفح قائمة الكورسات المتاحة مع كافة التفاصيل حول الأسعار ومدة الوصول والمحتوى.
    Browse the list of available courses with all the details about prices, access duration, and content.
@endsection
@section('style')
    .page-head { padding: var(--sp-16) 0 var(--sp-8); }
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--sp-6);
    }
    @media (max-width: 900px) { .courses-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 620px) { .courses-grid { grid-template-columns: 1fr; } }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: var(--sp-2);
        margin-top: var(--sp-16);
    }
    .pagination a, .pagination span, .pagination button {
        font-family: var(--font-mono);
        font-size: 13px;
        font-weight: 700;
        min-width: 36px;
        height: 36px;
        padding: 0 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--radius-pill);
        border: 1px solid var(--color-border-strong);
        color: var(--color-muted);
        background: var(--color-surface-raised);
        cursor: pointer;
        transition: border-color .15s ease, background .15s ease, color .15s ease;
    }
    .pagination a:hover:not(.is-active), .pagination button:hover:not(:disabled) {
        border-color: var(--color-brand);
        color: var(--color-brand-strong);
    }
    .pagination a.is-active {
        background: var(--color-primary);
        color: var(--color-on-primary);
        border-color: var(--color-primary);
        cursor: default;
    }
    .pagination .is-disabled, .pagination button:disabled {
        color: var(--color-faint);
        border-color: var(--color-border);
        background: transparent;
        cursor: not-allowed;
    }
    .pagination-note { text-align:center; margin-top: var(--sp-3); }
@endsection
@section('main')
    <header class="page-head">
        <div class="wrap">
            <div class="eyebrow mb-3">Full catalog</div>
            <h1 style="font-size:44px;">Every course, in one place.</h1>
            <p class="lede mt-3">Browse what's available — each course lists its price, how long you keep access, and what's inside before you commit.</p>
        </div>
    </header>
    <section class="section--tight" style="padding-top:0;">
        <div class="wrap">
            <div class="courses-grid">
                @foreach($courses as $course)
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
                            <div class="leader-row">
                                <span class="leader-row__label">Publish At</span>
                                <span class="leader-row__fill"></span>
                                <span class="leader-row__value">{{ \Carbon\Carbon::parse($course->publish_at)->tz('Africa/Cairo')->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            @if(method_exists($courses, 'links') && $courses->hasPages())
            <nav class="pagination" aria-label="Pagination">
                @if ($courses->currentPage() > 1)
                    <a href="{{ route('courses.index', ['page' => $courses->currentPage() - 1]) }}" aria-label="Previous page">&laquo;</a>
                @endif

                @for ($page = 1; $page <= $courses->lastPage(); $page++)
                    <a href="{{ route('courses.index', ['page' => $page]) }}" @class(['is-active' => $page == $courses->currentPage()]) @if ($page == $courses->currentPage()) aria-current="page" @endif>
                        {{ $page }}
                    </a>
                @endfor

                @if ($courses->currentPage() < $courses->lastPage())
                    <a href="{{ route('courses.index', ['page' => $courses->currentPage() + 1]) }}" aria-label="Next page">&raquo;</a>
                @endif
            </nav>

            <p class="pagination-note mono text-faint" style="font-size:11.5px;">
                page {{ $courses->currentPage() }} of {{ $courses->lastPage() }}
            </p>
            @endif
        </div>
    </section>
@endsection
