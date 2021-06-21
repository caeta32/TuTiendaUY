@extends('masterAdmin')

@section('sectionAdmin')
    <iframe name="frame" src="{{ url('/productos') }}" frameborder="0" style="display: block; border: none; width: 100%; height: 100%; overflow: hidden" scrolling="no"></iframe>
@endSection
