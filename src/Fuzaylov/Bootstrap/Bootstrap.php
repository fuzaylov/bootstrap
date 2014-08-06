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
		$attributes = array_merge($attributesDefault, $attributes);

		$html = $this->groupOpen();
		$html .= Form::label($field, $label);
		$html .= $this->fieldRequired();
//	    $attributes['required'] = 'required';
		$html .= Form::input($type, $field, $value, $attributes);
		$html .='</div>';

		$this->unsetRequired();

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
		return $this->input('confirm_password', $title, $value, $attributes, 'password');
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
		$attributes = array_merge($attributesDefault, $attributes);

		$html = $this->groupOpen();
		$html .= Form::label($field, $label);
		$html .= Form::select($field, $values, $valueSelected, $attributes);
		$html .= $this->groupClose();

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
		$statuses = array_merge($statusesDefault, $statuses);
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
		$attributes = array_merge($attributesDefault, $attributes);

		$html = $this->groupOpen();
		$html .= Form::label($field, $label);
		$html .= $this->fieldRequired();
		$html .= Form::textarea($field, $value, $attributes);
		$html .= $this->groupClose();

		$this->unsetRequired();

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
		$attributesDefault = ['class' => 'btn btn-primary btn-lg btn-block'];
		$attributes = array_merge($attributesDefault, $attributes);
		$html = $this->groupOpen();
		$html .= Form::submit($title, $attributes);
		$html .= $this->groupClose();

		return $html;
	}

	/**
	 * Set field required
	 * @return mixed
	 */
	public function required()
	{
		$this->isRequired = true;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function unsetRequired()
	{
		$this->isRequired = false;
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
}