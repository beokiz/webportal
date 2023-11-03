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
<div id="report-pdf-image-footer">
    <div class="first-row">

        <div>

        </div>

        <div>

        </div>

        <div>

        </div>
    </div>

    <div class="second-row">
        <div>
            <p>@lang('files.common.copyright')</p>
        </div>

        <div>

        </div>

        <div>
            @if(!empty($footerData['display_document_meta']))
                <p>@lang('files.pdf.page_info', ['current' => '<span class="page"></span>', 'total' => '<span class="topage"></span>'])</p>
            @endif

            @if(!empty($footerData['contact_email']))
                <a href="{{ "mailto:{$footerData['contact_email']}" }}" class="email-link">
                    {{ $footerData['contact_email'] }}
                </a>
            @endif
        </div>
    </div>
</div>
</body>
</html>
