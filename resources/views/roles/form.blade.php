{!! Form::model($role, ['route' => $role->id ? ['roles.update', $role->slug] : 'roles.create', 'method' => $role->id ? 'put' : 'post', 'class' => 'vertical', 'data-pjax' => '']) !!}
    <div class="row">
        <div class="column-half roles-left-column">
            <div class="control">
                <div class="control-label">
                    {!! Form::label('name', trans('roles.form.name.label')) !!}
                </div>
                <div class="control-field">
                    {!! Multilingual::text('name', $role) !!}
                    <div class="help-text">{!! trans('roles.form.name.hint') !!}</div>
                </div>
            </div>

            <div class="control">
                <div class="control-field">
                    <ul class="vertical">
                        <li>
                            {!! Form::checkbox('default', true, null, ['id' => 'default']) !!}
                            {!! Form::label('default', trans('roles.form.default.label'), ['for' => 'default']) !!}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="column-half roles-right-column">
            {!! HTML::permissionsMatrix($role) !!}
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="button primary big ladda-button" data-style="contract" data-spinner-color="#333">
            {!! trans('buttons.saveNext') !!}
        </button>
    </div>
{!! Form::close() !!}

