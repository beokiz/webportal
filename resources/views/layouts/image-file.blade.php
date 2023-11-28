<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    {{--    <link href="{{ resource_path('css/image.css') }}" rel="stylesheet">--}}
    {{--    <link href="{{ asset('css/image.css') }}" rel="stylesheet">--}}

    <style type="text/css">
        {!! file_get_contents(resource_path('css/images.css')) !!}
    </style>
</head>
<body>
<div id="app">
    <div id="report-pdf-image-header" class="image-report-header">

        <div>
            <img src="{{ base64_encode_file_to_uri(resource_path('images/logo.png')) }}"/>
        </div>
        <div>

        </div>
        <div>
            <ul>
                <li>@lang('files.common.tenant_line', ['id' => $headerData['tenant_id']])</li>
                <li>@lang('files.common.report_type_line', ['type' => $headerData['report_type']])</li>
                <li>@lang('files.common.report_period_line', ['from' => $headerData['from_time'], 'to' => $headerData['to_time']])</li>
                <li>@lang('files.common.created_date_line', ['date' => $headerData['current_time']])</li>
            </ul>
        </div>

    </div>

    <div id="report-body">

        @yield('content')

    </div>

    <div id="report-pdf-image-footer" class="image-report-footer">

        <div class="first-row">
            <div>
                <img src="{{ base64_encode_file_to_uri(resource_path('images/logo.png')) }}"/>
            </div>
            <div>

            </div>
            <div>
                <a href="mailto:{{ $footerData['info_email'] }}}}">
                    {{ $footerData['info_email'] }}
                </a>
            </div>
        </div>

        <div class="second-row">
            <div>
                <p>@lang('files.common.copyright')</p>
            </div>
            <div>

            </div>
            <div>

            </div>
        </div>

    </div>
</div>

@yield('scripts')
</body>
</html>
