<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{asset('admin_assets/img/apple-icon.png')}}">
    <link rel="icon" href="{{site_setting('1','2')}}">
    <title>
        {{site_setting('site_name')}}
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <link rel="stylesheet" href="{{asset('admin_assets/css/material-dashboard.css?v=2.0.1')}}">
    
    <link rel="stylesheet" href="{{ url('admin_assets/editor/editor.css') }}">
    <!-- Documentation extras -->
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{asset('admin_assets/assets-for-demo/demo.css')}}" rel="stylesheet" />
    <link href="{{asset('admin_assets/css/common.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ url('admin_assets/plugins/jQueryUI/jquery-ui.css') }}">

    <!-- iframe removal -->
    @if(navigation_active('admin.edit_help') ||navigation_active('admin.add_help') || navigation_active('admin.edit_static_page') || navigation_active('admin.add_static_page'))
        <link href="{{asset('admin_assets/css/gre.css')}}" rel="stylesheet" />
        
    @endif
</head>