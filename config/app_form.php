<?php
return [
    'input' => '<input class="form-control" type="{{type}}" name="{{name}}"{{attrs}}/>',
    'inputContainer' => '<div class="form-group">{{content}}</div>',
    'label' => '<label class="col-md-2 control-label" {{attrs}}>{{text}}</label>',
    'formGroup' => '{{label}}<div class="col-md-10">{{input}}</div>',
    'error' => '<span class="help-inline">{{content}}</span>',
    'inputContainerError' => '<div class="form-group has-error">{{content}}{{error}}</div>',
    'checkboxContainer' => '<div class="form-group">{{content}}</div>',
    'checkboxWrapper' => '<div class="col-md-offset-2 col-md-10"><div class="checkbox">{{label}}</div></div>',
    'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
    'multicheckboxWrapper' => '<div class="col-md-offset-2 col-md-10"><fieldset>{{content}}</fieldset></div>',
];
