<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- CSS only -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <style>
        @font-face {
            font-family: 'IBMPlexSansCondensed';
            src: url('fonts/IBMPlexSansCondensed-Light.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        html * {
            font-family: 'IBMPlexSansCondensed', sans-serif !important;
        }

        @page {
            size: a4 portrait;
            margin: 0.0;
            padding: 0.0;
        }

        .text-grey {
            color: #757575;
        }
    </style>
</head>

<body style="border-left: 30px {{ $enterprise->color }} solid; padding:50px 0px 50px 0px;margin-righ:35px;"">
    <div class="container-fluid" style="margin-left:35px;margin-right:35px;">
        <table class="table">
            <tbody>
                <tr style="padding:0 !important;margin:0 !important;">
                    <td style="padding:0 !important;margin:0 !important;">
                        <table class="table" style="padding:0 !important;margin:0 !important;">
                            <tbody>
                                <tr>
                                    <td>
                                        <h3 style="margin:0px 0px 1rem 0px;"><strong>{{ $type }}</strong></h3>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        <h4 style="margin:0.5rem 0px;line-height: 1;text-transform: uppercase;">
                                            <strong>{{ $enterprise->name }}</strong>
                                        </h4>
                                        <h5 class="text-grey" style="margin:0.5rem 0px;line-height: 1;">
                                            {{ $enterprise->address }}</h5>
                                        <h5 class="text-grey" style="margin:0.5rem 0px;line-height: 1;">
                                            {{ $enterprise->dpto }} -
                                            {{ $enterprise->province }} - {{ $enterprise->district }}</h5>
                                        <h5 class="text-grey" style="margin:0.5rem 0px;line-height: 1;">
                                            Telf:{{ $enterprise->phone_contact_one }} -
                                            {{ $enterprise->phone_contact_two }}</h5>
                                        <h5 class="text-grey" style="margin:0.5rem 0px;line-height: 1;">Email:
                                            {{ $enterprise->email }}
                                        </h5>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="padding:0 !important;margin:0 !important;">
                        <div style="width: 200px; height:200px;float:right;" class="mx-auto">
                            <img src={{ public_path($enterprise->logo) }} alt="logo" class="mx-auto"
                                style="width: 200px; height:200px;" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <div class="container-fluid" style="margin-left:35px;margin-right:35px;">
        <table class="table">
            <tbody>
                <tr>
                    <td style="margin:0px 0px 1rem 0px;">
                        <h4><strong>CLIENTE</strong></h4>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul style="list-style: none;margin:0px;padding:0px">
                            <li>
                                <h5 style="margin:0.5rem 0px;line-height: 1;"><strong>RUC: </strong>
                                    <span class="text-grey">{{ $client->dni }}</span>
                                </h5>

                            </li>
                            <li>
                                <h5 style="margin:0.5rem 0px;line-height: 1;"><strong>DENOMINACIÓN: </strong>
                                    <span class="text-grey">{{ $client->first_name }}</span>
                                </h5>

                            </li>
                            <li>
                                <h5 style="margin:0.5rem 0px;line-height: 1;"><strong>DIRECCIÓN:
                                    </strong> <span class="text-grey">{{ $enterprise->address }}</span> </h5>

                            </li>
                        </ul>
                    </td>
                    <td>
                        <ul style="list-style: none;margin:0px;padding:0px">
                            <li>
                                <h5 style="margin:0.5rem 0px;line-height: 1;"><strong>Fecha de emisión: </strong>
                                    <span class="text-grey"> @php echo date('d/m/Y h:i a', time()); @endphp </span>
                                </h5>

                            </li>
                            <li>
                                <h5 style="margin:0.5rem 0px;line-height: 1;"><strong>Fecha de venc.: </strong>
                                    <span class="text-grey"> @php echo date('d/m/Y h:i a', time()); @endphp </span>
                                </h5>

                            </li>
                            <li>
                                <h5 style="margin:0.5rem 0px;line-height: 1;">
                                    <strong>Moneda: </strong> <span class="text-grey">{{ $coin->name }}</span>
                                </h5>

                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>


    <div class="container-fluid py-4" style="margin-left:35px;margin-right:35px;">
        <table class="table table-striped">
            <thead style="">
                <tr style="border: 1px #000000 solid">
                    <th scope="col" class="align-top">
                        <h4 style="margin:0.5rem 0px;line-height: 1;text-align: start;">
                            <strong> Item </strong>
                        </h4>
                    </th>
                    <th scope="col" class="align-top text-center">
                        <h4 style="margin:0.5rem 0px;line-height: 1;">
                            <strong> Cantidad </strong>
                        </h4>
                    </th>
                    <th scope="col" class="align-top text-center">
                        <h4 class="text-center" style="margin:0.5rem 0px;line-height: 1;">
                            <strong> Precio Unitario </strong>
                        </h4>
                    </th>
                    <th scope="col" class="align-top">
                        <h4 class="text-end" style="margin:0.5rem 0px;line-height: 1;text-align: right;">
                            <strong> Total </strong>
                        </h4>
                    </th>
                </tr>


            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr
                        style="border-bottom: 1px #B0BEC5 solid;border-left: 1px #B0BEC5 solid;border-right: 1px #B0BEC5 solid;">
                        <td class="text-start"
                            style="max-width: 280px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;vertical-align: middle;">
                            <h4 style="margin:0.5rem 0px;line-height: 1;"><strong>{{ $product->product_name }}</strong>
                            </h4>
                            <h5 class="text-truncate d-inline-block text-grey"
                                style="margin:0.5rem 0px;line-height: 1;">
                                {{ $product->product_description }}
                            </h5>
                        </td>
                        <td class=" text-center" style="vertical-align: middle;">
                            <h4 class="text-grey" tyle="margin:0.5rem 0px;line-height: 1;">{{ $product->quantity }}
                            </h4>
                        </td>
                        <td class=" text-center" style="vertical-align: middle;">
                            <h4 class="text-grey" style="margin:0.5rem 0px;line-height: 1;">
                                {{ $coin->symbol . ' ' . $product->price_actual }}
                            </h4>
                        </td>
                        <td class="text-end" style="vertical-align: middle;">
                            <h4 class="text-grey" style="margin:0.5rem 0px;line-height: 1;text-align:right;">
                                {{ $coin->symbol . ' ' . number_format((float) $product->price_actual * $product->quantity, 2, '.', '') }}
                            </h4>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="container-fluid my-5 px-2" style="margin-left:35px;margin-right:35px;">
        <table class="table">

            <tbody>
                <tr style="color: {{ $enterprise->color }}">
                    <td class="text-start" style="width:50%;">
                        <h4 style="margin:0.5rem 0px;line-height: 1;"><strong>PRECIO TOTAL:</strong></h4>
                    </td>
                    <td class="text-end" style="width:50%;">
                        <h4 class="text-end" style="margin:0.5rem 2px 0.5rem 0px;line-height: 1;float:right;">
                            <strong
                                class="text-end">{{ $coin->symbol . ' ' . number_format((float) $total, 2, '.', '') }}</strong>
                        </h4>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="container-fluid my-5" style="margin-left:35px;margin-right:35px;">
        <table class="table">

            <tbody>
                <tr>
                    <td class=" text-start">
                        <h5 style="margin:0.5rem 0px;line-height: 1;" class="py-5"><strong>¡Gracias!</strong></h5>
                    </td>
                    <td class="text-justify">
                        <h5 style="margin:0.5rem 0px;line-height: 1;"><strong>Datos bancarios:
                            </strong></h5>

                        <h5 style="margin:0.5rem 0px;line-height: 1;"><strong>{{ $enterprise->banco }}</strong></h5>

                        <h5 style="margin:0.5rem 0px;line-height: 1;"><strong>Número de cuenta:</strong>
                        </h5>

                        <h5 class="text-grey" style="margin:0.5rem 0px;line-height: 1;">{{ $enterprise->nro_cta }} -
                            {{ $enterprise->propietary }}</h5>
                    </td>
                    <td class="text-justify">
                        <h5 class="text-grey" style="margin:0.5rem 0px;line-height: 1;">{{ $enterprise->email }}</h5>
                        <h5 style="margin:0.5rem 0px;line-height: 1;">Telf:
                            <span>{{ $enterprise->phone_contact_one }}</span> -
                            <span>{{ $enterprise->phone_contact_two }}</span>
                        </h5>
                        <h5 style="margin:0.5rem 0px;line-height: 1;">{{ $enterprise->address }}</h5>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
