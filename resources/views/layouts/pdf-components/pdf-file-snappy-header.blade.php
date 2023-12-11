<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    {{--    <link href="{{ resource_path('css/pdf.css') }}" rel="stylesheet">--}}
    {{--    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">--}}

    <style type="text/css">
        {!! file_get_contents(resource_path('css/pdf.css')) !!}
    </style>

    <script type="text/javascript">
        function parseSnappyPdfVariables() {
            var vars = {};
            var x = document.location.search.substring(1).split('&');
            for (var i in x) {
                var z = x[i].split('=', 2);
                vars[z[0]] = unescape(z[1]);
            }
            var x = ['frompage', 'topage', 'page', 'webpage', 'section', 'subsection', 'subsubsection'];
            for (var i in x) {
                var y = document.getElementsByClassName(x[i]);
                for (var j = 0; j < y.length; ++j) y[j].textContent = vars[x[i]];
            }
        }
    </script>
</head>

<body onload="parseSnappyPdfVariables()">
<div id="report-pdf-image-header">
    <div>
        <img src="{{ resource_path('images/logo-pdf.jpg') }}"/>
    </div>

    <div>

    </div>

    <div>
        <ul>
            @if(!empty($headerData['report_date']))
                <li>@lang('files.common.report_date_line', ['date' => $headerData['report_date']])</li>
            @endif
            <li>@lang('files.common.created_date_line', ['date' => $headerData['current_time']])</li>
        </ul>
    </div>
</div>
</body>
</html>
