<div class="container">
    {{ Form::model($role, ['action' => 'Tectonic\Shift\Controllers\RoleController@postStore', 'class' => 'vertical']) }}
        <div class="row">
            <div class="column-half roles-left-column">
                <div class="control">
                    <div class="control-label">
                        {{ Form::label('name', trans('shift::roles.form.name.label')) }}
                    </div>
                    <div class="control-field">
                        {{ Multilingual::text('name') }}
                        <div class="help-text">{{ trans('shift::roles.form.name.hint') }}</div>
                    </div>
                </div>

                <div class="control">
                    <div class="control-field">
                        <ul class="vertical">
                            <li>
                                {{ Form::checkbox('default') }}
                                {{ Form::label('default', trans('shift::roles.form.default.label')) }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <input type="submit" class="button primary" value="{{ trans('shift::buttons.saveNext') }}">
        </div>
    {{Form::close()}}
</div>
