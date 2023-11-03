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

        #report-pdf-image-header {
            position: relative;
            top: -.32cm;
            margin: 0 .755cm;
            width: 100%;
            font-family: "OpenSans-Regular", sans-serif;
            font-size: .21cm;
            line-height: .2cm;
            color: #181818;
        }

        #report-pdf-image-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            /*border-bottom: 1px solid #f0f0f0;*/
            padding-bottom: 10px;
        }

        #report-pdf-image-header > div:nth-of-type(1){
            width: 15%;
        }

        #report-pdf-image-header > div:nth-of-type(2) {
            width: 32%;
        }

        #report-pdf-image-header > div:nth-of-type(3) {
            width: 53%;
            text-align: right;
        }

        #report-pdf-image-header > div:nth-of-type(1) img {
            position: relative;
            top: -.125cm;
            max-height: .56cm;
        }

        #report-pdf-image-header > div:nth-of-type(3) ul {
            margin: 0;
            padding: 10px 0 15px 0;
        }

        #report-pdf-image-header > div:nth-of-type(3) ul li {
            list-style-type: none;
            padding-bottom: 5px;
        }

        #report-pdf-image-header > div:nth-of-type(3) ul li:nth-last-of-type(1) {
            padding-bottom: 0;
        }
    </style>
</head>
<body>
<div id="report-pdf-image-header">

    <div>
        <img src="{{ base64_encode_file_to_uri(resource_path('images/logo-pdf.jpg')) }}"/>
    </div>
    <div>

    </div>
    <div>
        <ul>
            @if(!empty($headerData['report_date']))
                <li>@lang('files.common.created_date_line', ['date' => $headerData['report_date']])</li>
            @endif
            <li>@lang('files.common.created_date_line', ['date' => $headerData['current_time']])</li>
        </ul>
    </div>

</div>
</body>
</html>
