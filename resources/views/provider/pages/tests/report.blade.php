
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 16px;
            line-height: normal;
        }

        p {
            margin: 0;
        }

        td {
            vertical-align: middle;
        }

        .items thead td {
            border-bottom: 1px solid rgb(11 18 30 / 89%);
        }

        .items td {
            border-bottom: 1px solid rgba(10,29,60,0.25);
        }

        table thead td {
            text-align: center;
        }

        .items td.totals {
            text-align: right;
            border: 1px solid rgba(10,29,60,0.25);
        }

        .items td.cost {
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }

        .logo {
            height: 70px;
            width: 180px;
            overflow: hidden;
            position: relative;
        }

        img {
            height: 100%;
            width: 100%;
            object-fit: contain;
        }

    </style>
</head>
<body style="font-family: sans-serif; padding: 0 30px; margin: 0 auto;" cellpadding="10">

<table width="100%" style="font-family: sans-serif; max-width: 1000px; margin: 0 auto;border: 0;" cellpadding="10">
    <tbody>
    <tr>
        <td width="100%" style="padding: 0px; text-align: left;">
            <!-- spacer -->
            <table width="100%" style="font-family: sans-serif; border:0;" cellpadding="10" height="50">
                <tr>
                    <td width="100%" style="padding: 0px; text-align: left;">
                    </td>
                </tr>
            </table>
            <!-- spacer -->

            <!-- header -->
            <table width="100%" style="font-family: sans-serif;" cellpadding="10">
                <tr>
                    <td width="100%" style="padding: 0px; text-align: left;">
                        <div class="logo">
{{--                            <img src="{{ url('frontend/assets/img/logo.png') }}"  width="145px" height="35" alt="" align="center" border="0">--}}
                        </div>
                    </td>
                </tr>

                <!-- spacer -->
                <tr>
                    <td width="100%" height="15" style="padding: 0px; text-align: left;">
                    </td>
                </tr>
                <!-- spacer -->
                <tr>
                    <td width="100%" style="text-align: left; font-size: 20px; font-weight: bold; padding: 0px;">
                        Test Report {{ $data->patient->name }}
                    </td>
                </tr>
                <tr>
                    <td height="10" style="font-size: 0px; line-height: 10px; height: 10px; padding: 0px;">&nbsp;</td>
                </tr>
            </table>
            <!-- header -->

            <!-- spacer -->
            <table width="100%" style="font-family: sans-serif;" cellpadding="10" height="25">
                <tr>
                    <td width="100%" style="padding: 0px; text-align: left;">
                    </td>
                </tr>
            </table>
            <!-- spacer -->

            <!-- body -->

            <table class="items" width="100%" style="font-size: 14px; border-collapse: collapse;" cellpadding="8">
                <thead>
                <tr>
                    <td width="30%" style="text-align: left;"><strong>Name</strong></td>
                    <td width="15%" style="text-align: left;"><strong>Patient Name</strong></td>
                    <td width="20%" style="text-align: left;"><strong>Comment</strong></td>
                </tr>
                </thead>
                <tbody>
                <!-- ITEMS HERE -->
                <tr>
                    <td style="font-size:16px;padding: 10px; line-height: 20px;">asdfasdf</td>
                    <td style="font-size:16px;padding: 10px; line-height: 20px;">adfadsf</td>
                    <td style="font-size:16px;padding: 10px; line-height: 20px;">adfadfaf</td>
                </tr>
                </tbody>
{{--                @foreach ($data as $key => $item)--}}
{{--                    <tbody>--}}
{{--                    <!-- ITEMS HERE -->--}}
{{--                    <tr>--}}
{{--                        <td style="font-size:16px;padding: 10px; line-height: 20px;">{{ $item["name"] }}</td>--}}
{{--                        <td style="font-size:16px;padding: 10px; line-height: 20px;">{{ $item->patient["name"] ?? ""}}</td>--}}
{{--                        <td style="font-size:16px;padding: 10px; line-height: 20px;">{{ $item["comment"] }}</td>--}}
{{--                    </tr>--}}
{{--                    </tbody>--}}
{{--                @endforeach--}}
            </table>
            <!-- body -->

            <!-- spacer -->
            <table width="100%" style="font-family: sans-serif;" cellpadding="10" height="50">
                <tr>
                    <td width="100%" style="padding: 0px; text-align: left;">
                    </td>
                </tr>
            </table>
            <!-- spacer -->
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
