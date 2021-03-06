@section('main')
    <div class="row island">
        <div class="column-half">
            <div class="title">
                <h1>
                    <a href="{{ route('users.index') }}">{{ trans('users.titles.main')}}</a>
                    &gt; {{ HTML::fullName($user) }}
                </h1>
            </div>
        </div>
    </div>

    @include('shift::partials.errors.display')

    <div class="row">
        @include('shift::users.form')
    </div>
@stop
