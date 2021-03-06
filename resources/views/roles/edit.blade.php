@section('main')
    <div class="row island">
        <div class="column-half">
            <div class="title">
                <h1>
                    <a href="{{ route('roles.index') }}">{{ trans('roles.titles.main')}}</a>
                    &gt; {{ lang($role, 'name') }}
                </h1>
            </div>
        </div>
    </div>

    @include('shift::partials.errors.display')

    <div class="row">
        @include('shift::roles.form')
    </div>
@stop
