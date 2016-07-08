<?php

function navbar_url($navbar_label,$narbar_url)
{
    $CI =& get_instance();
    $visiting_url=$CI->uri->segment(1).'/'.$CI->uri->segment(2);
    $reset_url_array=  explode('/', $narbar_url);
    $reset_url=$reset_url_array[0].'/'.$reset_url_array[1];
    if($reset_url===$visiting_url)
    {
        return '<li class="active"><a href="javascript:;">'.$navbar_label.'<span class="sr-only">(current)</span></a></li>';
    }
    else
    {
        return '<li><a href="'.base_url().$narbar_url.'">'.$navbar_label.'</a></li>';
    }
}