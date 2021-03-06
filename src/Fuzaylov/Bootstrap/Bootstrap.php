<?php namespace Fuzaylov\Bootstrap;

use Form;
use URL;
use Request;

/**
 * Class Bootstrap
 * @package Fuzaylov\Bootstrap
 */
class Bootstrap {

    /**
     * @var bool
     */
    private $isRequired = false;
    
    private $isRequiredHtml5 = false;

    /**
     * @var array
     */
    private $appendAttributes = [];

    /**
     * Generate nav bar item which does not require dropdown menu
     * @param $navTab
     * @param $route
     * @param $title
     * @param $currentNavTab -- defaults to Request::segment(1)
     *
     * @return string
     */
    public function navbar( $navTab, $route, $title, $currentNavTab = null )
    {
        $currentNavTab = $currentNavTab ?: Request::segment(1);
        $active = $currentNavTab == $navTab ? 'active' : '';
        return '
        <li class="dropdown ' . $active . '">
            <a href="' . URL::route($route) . '">' . $title . '</a>
        </li>
    ';
    }

    /**
     * Generate nav bar items under the same dropdown menu
     * @param $navTab
     * @param $title -- title of the dropdown menu
     * @param $menus -- array in the format: 'route', 'title'
     * @param $currentNavTab -- defaults to Request::segment(1)
     *
     * @return string
     */
    public function navbarDropdown( $navTab, $title, $menus, $currentNavTab = null )
    {
        $currentNavTab = $currentNavTab ?: Request::segment(1);
        $active = $currentNavTab == $navTab ? 'active' : '';
        $html = '
            <li class="dropdown ' . $active . '">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $title . ' <b class="caret"></b></a>
                <ul class="dropdown-menu">
        ';

        foreach ($menus as $route => $title) {
            $html .= '<li><a href="' . URL::route($route) . '">' . $title . '</a></li>';
        }

        $html .= '
                </ul>
            </li>
        ';

        return $html;
    }

    /**
     * Generate asset upload field
     * @param      $assetFieldName
     * @param      $assetPath
     * @param bool $preview
     *
     * @return string
     */
    public function assetUpload( $assetFieldName, $assetPath, $preview = true)
    {
        $html = $this->groupOpen();
        $html .= Form::file($assetFieldName, array('class' => 'file-input'));
        if (!empty($assetPath)) {
            if ($preview) {
                $html .= '
                    <a rel="shadowbox" style="max-width: 300px;" class="thumbnail" href="' . $assetPath . '">
                        <img src="' . $assetPath . '" />
                    </a>';
            } else {
                $html .= '
                <a href="' . $assetPath . '" target="_blank">Download</a>
            ';
            }
        }
        $html .= '
            </div>
        ';
        return $html;
    }

    /**
     * Generate a generic input field
     * @param        $field
     * @param        $label
     * @param        $value
     * @param array  $attributes
     * @param string $type
     *
     * @return string
     */
    public function input( $field, $label, $value, $attributes = [], $type = 'text' )
    {
        $attributesDefault = ["class" => 'form-control'];
        $attributes = $this->getAttributes($attributesDefault, $attributes);

        $html = $this->groupOpen();
        $html .= Form::label($field, $label, ['class' => "col-md-4 control-label"]);
        $html .= $this->fieldRequired();
//	    $attributes['required'] = 'required';
        $html .= '<div class="col-md-6">';
            $html .= Form::input($type, $field, $value, $attributes);
        $html .= '</div>';
        $html .='</div>';

        $this->reset();

        return $html;
    }

    /**
     * Generate a generic text field
     * @param       $field
     * @param       $label
     * @param       $value
     * @param array $attributes
     *
     * @return string
     */
    public function text( $field, $label, $value, $attributes = [] )
    {
        return $this->input($field, $label, $value, $attributes, 'text');
    }

    /**
     * Generate a required title field
     * @param       $value
     * @param array $attributes
     *
     * @return string
     */
    public function title($value, $attributes = [])
    {
        return $this->required()->text('title', 'Title', $value, $attributes, true);
    }

    /**
     * Generate a password field
     *
     * @param        $value
     * @param string $title
     * @param array  $attributes
     *
     * @return string
     */
    public function password($value, $title = 'Password', $attributes = [])
    {
        return $this->input('password', $title, $value, $attributes, 'password');
    }

    /**
     * Generate a confirm_password field
     * @param        $value
     * @param string $title
     * @param array  $attributes
     *
     * @return string
     */
    public function confirm_password($value, $title = 'Confirm Password', $attributes = [])
    {
        return $this->input('password_confirmation', $title, $value, $attributes, 'password');
    }

    /**
     * Generate a date field
     * @param       $value
     * @param array $attributes
     *
     * @return string
     */
    public function date( $value, $attributes = [] )
    {
        return $this->input('date', 'Date', $value, $attributes, 'date');
    }

    /**
     * Generate a select field
     * @param       $field
     * @param       $label
     * @param       $values
     * @param       $valueSelected
     * @param array $attributes
     *
     * @return string
     */
    public function select( $field, $label, $values, $valueSelected, $attributes = [] )
    {
        $attributesDefault = ["class" => 'form-control'];
        $attributes = $this->getAttributes($attributesDefault, $attributes);

        $html = $this->groupOpen();
        $html .= Form::label($field, $label, ['class' => "col-md-4 control-label"]);
        $html .= '<div class="col-md-6">';
            $html .= Form::select($field, $values, $valueSelected, $attributes);
        $html .= '</div>';
        $html .= $this->groupClose();

        return $html;
    }

    /**
     * @param $field
     * @param $label
     * @param $values
     * @param $valueChecked
     *
     * @return string
     */
    public function radio( $field, $label, $values, $valueChecked ) {
        $html = '
            <div class="control-group">
                <label class="control-label">' . $label . ':</label>
                <div class="controls">
                    <div class="btn-group" data-toggle="buttons-radio">
        ';
        foreach ($values as $key => $val) {
            $html .= '<button type="button" data-toggle="button" name="' . $field . '" value="' . $key . '" class="btn btn-xs btn-danger radio-button">' . $val . '</button>';
        }
        $html .= '
                    </div>
                </div>
            </div>
            <input type="hidden" name="' . $field . '" value="{!! old(\'' . $field . '\') or ' . $valueChecked . ' !!}" id="' . $field . '">
        ';

        return $html;
    }

    /**
     * Generate a status select field
     * @param       $value
     * @param array $statuses
     *
     * @return string
     */
    public function status( $value, $statuses = [] )
    {
        $statusesDefault = [
            '1' => 'Enabled',
            '0' => 'Disabled',
        ];
        $statuses = array_replace($statusesDefault, $statuses);
        return $this->required()->select('status', 'Status', $statuses, $value);
    }

    /**
     * Generate a textarea field with an optional WYSIWYG editor
     * @param       $field
     * @param       $label
     * @param       $value
     * @param array $attributes
     * @param bool  $isWysiwyg
     *
     * @return string
     */
    public function textarea( $field, $label, $value, $attributes = [], $isWysiwyg = false )
    {
        $attributesDefault = ["class" => 'form-control', 'rows' => 3];
        if ($isWysiwyg) {
            $attributesDefault['class'] .= ' wysiwyg';
        }
        $attributes = $this->getAttributes($attributesDefault, $attributes);

        $html = $this->groupOpen();
        $html .= Form::label($field, $label);
        $html .= $this->fieldRequired();
        $html .= Form::textarea($field, $value, $attributes);
        $html .= $this->groupClose();

        $this->reset();

        return $html;
    }

    /**
     * Generate a textarea field with a WYSIWYG editor
     * @param       $field
     * @param       $label
     * @param       $value
     * @param array $attributes
     *
     * @return string
     */
    public function wysiwyg( $field, $label, $value, $attributes = [] )
    {
        return $this->textarea($field, $label, $value, $attributes, true);
    }

    /**
     * Generate a submit button
     * @param string $title
     * @param array  $attributes
     *
     * @return string
     */
    public function submit( $title = 'Submit', $attributes = [ ] )
    {
        $attributesDefault = ['class' => 'btn btn-primary'];
        $attributes = $this->getAttributes($attributesDefault, $attributes);
        $html = $this->groupOpen();
        $html .= '<div class="col-md-6 col-md-offset-4">';
            $html .= Form::submit($title, $attributes);
        $html .= '</div>';
        $html .= $this->groupClose();

        return $html;
    }

    /**
     * Set field required
     * @return mixed
     */
    public function required($isRequiredHtml5 = false)
    {
        $this->isRequired = true;
        $this->isRequiredHtml5 = $isRequiredHtml5;
        return $this;
    }

    public function appendAttr( array $attributes )
    {
        $this->appendAttributes = array_replace($this->appendAttributes, $attributes);
        return $this;
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->isRequired = false;
        $this->isRequiredHtml5 = false;
        $this->appendAttributes = [];
        return $this;
    }

    /**
     * Form group open helper
     * @return string
     */
    public function groupOpen()
    {
        return '<div class="form-group">';
    }

    /**
     * Form group close helper
     * @return string
     */
    public function groupClose()
    {
        return '</div>';
    }

    /**
     * Generate a required span with a star
     * @return string
     */
    public function fieldRequired()
    {
        if ($this->isRequired) {
            return '<span class="text-warning field-required">*</span>';
        }

        return '';
    }

    private function getAttributes( $defaultAttributes, $userAttributes )
    {

        // merge default with user attributes
        $newAttributes = array_replace($defaultAttributes, $userAttributes);

        // append values to existing attributes
        foreach ($newAttributes as $name => $val) {
            if (isset($this->appendAttributes[$name])) {
                $newAttributes[$name] .= ' ' . $this->appendAttributes[$name];
            }
        }
        
        if ($this->isRequiredHtml5) {
            $newAttributes['required'] = 'required';
        }

        return $newAttributes;
    }
}