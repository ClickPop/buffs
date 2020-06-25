@extends('errors::buffs')

@section('title', __('Not Approved'))
@section('code', '420')
@section('message',  __($exception->getMessage() ?: 'Beta key not approved'))
