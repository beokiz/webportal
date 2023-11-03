<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <style type="text/css">
        /*********************************/
        /********* Common styles *********/
        /*********************************/
        body {
            margin: 0;
        }

        #report-pdf-image-footer {
            position: relative;
            /*top: -.32cm;*/
            display: flex;
            flex-direction: column;
            margin: 0 .755cm;
            width: 100%;
            font-family: "OpenSans-Regular", sans-serif;
            font-size: .21cm;
            color: #181818;
        }

        #report-pdf-image-footer > div {
            display: flex;
            align-items: center;
            padding-bottom: .1cm;
        }

        #report-pdf-image-footer > div:nth-last-of-type(1) {
            padding-bottom: 0;
        }

        #report-pdf-image-footer > div > div:nth-of-type(1) {
            width: 35%;
        }

        #report-pdf-image-footer > div > div:nth-of-type(2) {
            width: 35%;
        }

        #report-pdf-image-footer > div > div:nth-of-type(3) {
            width: 30%;
            text-align: right;
        }

        #report-pdf-image-footer > div > div:nth-of-type(1) img {
            position: relative;
            top: .25cm;
            max-height: .56cm;
        }

        #report-pdf-image-footer > div > div:nth-of-type(1) p {
            position: relative;
            top: .2cm;
            left: -.025cm;
            font-size: .225cm;
        }

        #report-pdf-image-footer > div > div:nth-of-type(3) a {
            position: relative;
            top: .27cm;
            font-family: "OpenSans-ExtraBold", sans-serif;
            font-size: .25cm;
            color: #000000;
            text-decoration: none;
        }

        #report-pdf-image-footer > div > div:nth-of-type(3) p {
            position: relative;
            top: .215cm;
            font-size: .23cm;
        }
    </style>
</head>

<body>
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
                <p>@lang('files.pdf.page_info', ['current' => '<span class="pageNumber"></span>', 'total' => '<span class="totalPages"></span>'])</p>
            @endif

            @if(!empty($footerData['contact_email']))
                <a href="{{ "mailto:{$footerData['contact_email']}" }}">{{ $footerData['contact_email'] }}</a>
            @endif
        </div>
    </div>
</div>
</body>
</html>
