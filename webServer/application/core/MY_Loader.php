<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
    public function __construct()
    {
        parent::__construct();
        $this->_ci_api_paths = array(APPPATH);
    }

    public function initialize()
    {
        parent::initialize();
        $this->_ci_api_files = array();
    }
    
    public function field($class,$show_name,$name,$is_must_input=FALSE)
    {
        if ($class == '')
        {
            return;
        }

        $path = '';

        // Is the model in a sub-folder? If so, parse out the filename and path.
        if (($last_slash = strrpos($class, '/')) !== FALSE)
        {
            // The path is in front of the last slash
            $path = substr($class, 0, $last_slash + 1);

            // And the model name behind it
            $model = substr($class, $last_slash + 1);
        }

        if ($name == '')
        {
            $name = $class;
        }
        $class = strtolower($class);
        foreach ($this->_ci_api_paths as $api_path)
        {
            if ( ! file_exists($api_path.'models/fields/'.$path.$class.'.php'))
            {
                continue;
            }
            require_once($api_path.'models/fields/'.$path.$class.'.php');

            $class = ucfirst($class);

            return new $class($show_name,$name,$is_must_input);
        }

        // couldn't find the model
        show_error('Unable to locate the field you have specified: '.$class);

    }
    
    public function classes($class)
    {
        if ($class == '')
        {
            return;
        }

        $path = '';

        // Is the model in a sub-folder? If so, parse out the filename and path.
        if (($last_slash = strrpos($class, '/')) !== FALSE)
        {
            // The path is in front of the last slash
            $path = substr($class, 0, $last_slash + 1);

            // And the model name behind it
            $model = substr($class, $last_slash + 1);
        }

        $class = strtolower($class);
        foreach ($this->_ci_api_paths as $api_path)
        {
            if ( ! file_exists($api_path.'classes/'.$path.$class.'.php'))
            {
                continue;
            }
            require_once($api_path.'classes/'.$path.$class.'.php');

            $class = ucfirst($class);

            return new $class();
        }

        // couldn't find the model
        show_error('Unable to locate the class you have specified: '.$class);

    }

    public function api($api, $name = '')
    {
        if (is_array($api))
        {
            foreach ($api as $babe)
            {
                $this->api($babe);
            }
            return;
        }

        if ($api == '')
        {
            return;
        }

        $path = '';

        // Is the model in a sub-folder? If so, parse out the filename and path.
        if (($last_slash = strrpos($api, '/')) !== FALSE)
        {
            // The path is in front of the last slash
            $path = substr($api, 0, $last_slash + 1);

            // And the model name behind it
            $model = substr($api, $last_slash + 1);
        }

        if ($name == '')
        {
            $name = $api;
        }

        if (in_array($name, $this->_ci_api_files, TRUE))
        {
            return;
        }
        $CI =& get_instance();
        if (isset($CI->$name))
        {
            show_error('The api name you are loading is the name of a resource that is already being used: '.$name);
        }

        $api = strtolower($api);
        foreach ($this->_ci_api_paths as $api_path)
        {
            if ( ! file_exists($api_path.'api/'.$path.$api.'.php'))
            {
                continue;
            }

            /*if ( ! class_exists('CI_Api'))
            {
                load_class('Api', 'core');
            }
             */

            require_once($api_path.'api/'.$path.$api.'.php');

            $api = ucfirst($api);

            $CI->$name = new $api();

            $this->_ci_api_files[] = $name;
            return;
        }

        // couldn't find the model
        show_error('Unable to locate the api you have specified: '.$api);

    }

}
