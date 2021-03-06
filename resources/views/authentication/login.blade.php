<h1>Log in</h1>

<!-- Login form -->
{!! Form::open(['action' => '\Tectonic\Shift\Controllers\AuthenticationController@login', 'class' => 'vertical well']) !!}

    <div class="control">
        <div class="control-label">
            {!! Form::label('email', 'Email') !!}
        </div>
        <div class="control-field">
            {!! Form::email('email') !!}
        </div>
    </div>

    <div class="control">
        <div class="control-label">
            {!! Form::label('password', 'Password') !!}
        </div>
        <div class="control-field">
            {!! Form::password('password') !!}
        </div>
    </div>

    <div class="control">
        <div class="control-label">
            {!! Form::label('remember', 'Remember me') !!}
        </div>
        <div class="control-field">
            {!! Form::checkbox('remember') !!}
        </div>
    </div>

    <div class="control">
        <div class="control-field">
            <a href="#">Have you forgotten you password?</a>
        </div>
    </div>

    <div class="control">
        <div class="control-field">
            <input type="submit" value="Log in">
        </div>
    </div>

{!! Form::close() !!}
