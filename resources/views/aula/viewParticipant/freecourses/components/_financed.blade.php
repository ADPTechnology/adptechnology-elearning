<div class="financial-header w-100">

    <table class="table table-responsive w-100">
        <thead>
            <tr>
                <th scope="col" class="bg-green text-center w-300">Ticket de Empresa</th>
                <th scope="col" class="bg-green text-center w-200">Intel Corp</th>
                {{-- <th scope="col" class="bg-blue text-end w-400">250 Day Chart</th> --}}
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row" class="text-center">
                    {{ $data['Nombre'] }}
                </th>
                <td>{{ $data['Moneda'] }}</td>
                <td></td>
            </tr>
            <tr>
                <th scope="row" class="bg-blue text-center">Precio Actual</th>
                <td>{{ $data['Precio Actual'] }}</td>
                <td></td>
            </tr>
            <tr>
                <th scope="row" class="bg-blue text-center">EPS (ttm)</th>
                <td>{{ $data['EPS (ttm)'] }}</td>
                <td></td>
            </tr>
            <tr>
                <th scope="row" class="bg-blue text-center">EPS Estimado Next Year</th>
                <td> {{ $data['EPS Estimado Next Year'] }} </td>
                <td class="text-center"></td>
            </tr>
            <tr>
                <th scope="row" class="bg-blue text-center">Ventas promedio Past 5 Y</th>
                <td> {{ $data['Ventas promedio Past 5 Y'] }} </td>
                <td></td>
            </tr>
            <tr>
                <th scope="row" class="bg-blue text-center">EPS promedio Past 5 Y</th>
                <td> {{ $data['EPS promedio Past 5 Y'] }} </td>
                <td></td>
            </tr>
            <tr>
                <th scope="row" class="bg-blue text-center">PER (ttm)</th>
                <td> {{ $data['PER (ttm)'] }} </td>
                <td></td>
            </tr>
            <tr>
                <th scope="row" class="bg-blue text-center">FORWARD P/E</th>
                <td> {{ $data['FORWARD P/E'] }} </td>
                <td></td>
            </tr>
        </tbody>
    </table>

</div>

<br><br>

{{-- CUADRO 1 --}}

@php
    $cabeceras = $cuadro1['cabeceras'];
    $countHeaders = count($cabeceras);
@endphp
<div style="overflow: auto;">
    <table class="table table-borderless finance_table">
        <thead class="thead-light">
            <tr>
                @foreach ($cabeceras as $cabecera)
                <th class="text-bold">
                    {{ $cabecera }}
                </th>
                @endforeach
            </tr>
        </thead>

        <tbody>

            <tr>
                @php
                    $totalRevenue = $cuadro1['Total Revenue'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                <td>
                    @if (isset($totalRevenue['datos'][$i]))
                        ${{ $totalRevenue['datos'][$i] ?? '-' }}
                    @endif
                </td>
                @endfor
                <td class="text-bold title">
                    {{ $totalRevenue['titulo'] }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $totalRevenueYoY = $cuadro1['Total Revenue - % Change YoY'];
                @endphp
                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($totalRevenueYoY['datos'][$i]))
                    @php
                        $class = strpos($totalRevenueYoY['datos'][$i], '-') !== false ? 'text-red': 'text-green';
                    @endphp
                    @endif
                <td class="{{ $class ?? '' }}">
                    {{ $totalRevenueYoY['datos'][$i] ?? '' }}
                </td>
                @endfor
                <td class="font-italic text-small">
                    {{ $totalRevenueYoY['titulo'] }}
                </td>
            </tr>

            <tr>
                @php
                    $costRevenue = $cuadro1['Cost of Revenue'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($costRevenue['datos'][$i]))
                        <td>
                            ${{ $costRevenue['datos'][$i] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $costRevenue['titulo'] }}
                </td>
            </tr>

            <tr class="border-bottom">
                @php
                    $grossProfit = $cuadro1['Gross Profit'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($grossProfit['datos'][$i]))
                        <td>
                            ${{ $grossProfit['datos'][$i] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $grossProfit['titulo'] }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $grossProfitYoY = $cuadro1['Gross Profit - % Change YoY'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($grossProfitYoY['datos'][$i]))
                        @php
                        $class = strpos($grossProfitYoY['datos'][$i], '-') !== false ? 'text-red': 'text-green';
                        @endphp
                    @endif
                    <td class="{{ $class ?? '' }}">
                        {{ $grossProfitYoY['datos'][$i] ?? '' }}
                    </td>
                @endfor
                <td class="text-small">
                    {{ $grossProfitYoY['titulo'] }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $grossProfitMargins = $cuadro1['Gross Profit - % Gross Margins'];
                @endphp
                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($grossProfitMargins['datos'][$i]))
                        @php
                            $class = strpos($grossProfitMargins['datos'][$i], '-') !== false ? 'text-red': 'text-green';
                        @endphp
                    @endif
                    <td class="{{ $class ?? '' }}">
                        {{ $grossProfitMargins['datos'][$i] ?? ''}}
                    </td>
                @endfor
                <td class="font-italic text-small">
                    {{ $grossProfitMargins['titulo'] }}
                </td>
            </tr>

            <tr>
                @php
                    $rDevelopment = $cuadro1['R & Development'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($rDevelopment['datos'][$i]))
                        <td>
                            ${{ $rDevelopment['datos'][$i] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $rDevelopment['titulo'] }}
                </td>
            </tr>

            <tr>
                @php
                    $selling = $cuadro1['Selling General & Admin Expenses'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($selling['datos'][$i]))
                        <td>
                            ${{ $selling['datos'][$i] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $selling['titulo'] }}
                </td>
            </tr>

            <tr class="border-bottom">
                @php
                    $opIncome = $cuadro1['Op. Income'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($opIncome['datos'][$i]))
                        <td>
                            ${{ $opIncome['datos'][$i] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $opIncome['titulo'] }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $opIncomeYoY= $cuadro1['Op. Income - % Change YoY'];
                @endphp
                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($opIncomeYoY['datos'][$i]))
                        @php
                            $class = strpos($opIncomeYoY['datos'][$i], '-') !== false ? 'text-red': 'text-green';
                        @endphp
                    @endif
                    <td class="{{ $class ?? '' }}">
                        {{ $opIncomeYoY['datos'][$i] ?? ''}}
                    </td>
                @endfor
                <td class="font-italic text-small">
                    {{ $opIncomeYoY['titulo'] }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $opIncomeMargins = $cuadro1['Op. Income - % Operating Margins'];
                @endphp
                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($opIncomeMargins['datos'][$i]))
                        @php
                            $class = strpos($opIncomeMargins['datos'][$i], '-') !== false ? 'text-red': 'text-green';
                        @endphp
                    @endif
                    <td class="{{ $class ?? '' }}">
                        {{ $opIncomeMargins['datos'][$i] ?? ''}}
                    </td>
                @endfor
                <td class="font-italic text-small">
                    {{ $opIncomeMargins['titulo'] }}
                </td>
            </tr>

            <tr>
                @php
                    $incomeLoss = $cuadro1['Income (Loss) Pre Taxes'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($incomeLoss['datos'][$i]))
                        <td>
                            ${{ $incomeLoss['datos'][$i] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $incomeLoss['titulo'] }}
                </td>
            </tr>

            <tr>
                @php
                    $incomeTax = $cuadro1['Income Tax Expense'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($incomeTax['datos'][$i]))
                        <td>
                            ${{ $incomeTax['datos'][$i] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $incomeTax['titulo'] }}
                </td>
            </tr>

            <tr>
                @php
                    $incomeTax = $cuadro1['Income Tax Expense'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($incomeTax['datos'][$i]))
                        <td>
                            ${{ $incomeTax['datos'][$i] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $incomeTax['titulo'] }}
                </td>
            </tr>

            <tr class="border-bottom">
                @php
                    $netIncome = $cuadro1['Net Income'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($netIncome['datos'][$i]))
                        <td>
                            ${{ $netIncome['datos'][$i] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $netIncome['titulo'] }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $netIncomeYoY = $cuadro1['Net Income - % Change YoY'];
                @endphp
                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($netIncomeYoY['datos'][$i]))
                        @php
                            $class = strpos($netIncomeYoY['datos'][$i], '-') !== false ? 'text-red': 'text-green';
                        @endphp
                    @endif
                    <td class="{{ $class ?? '' }}">
                        {{ $netIncomeYoY['datos'][$i] ?? ''}}
                    </td>
                @endfor
                <td class="font-italic text-small">
                    {{ $netIncomeYoY['titulo'] }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $netIncomeMargins = $cuadro1['Net Income - % Income Margins'];
                @endphp
                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($netIncomeMargins['datos'][$i]))
                        @php
                            $class = strpos($netIncomeMargins['datos'][$i], '-') !== false ? 'text-red': 'text-green';
                        @endphp
                    @endif
                    <td class="{{ $class ?? '' }}">
                        {{ $netIncomeMargins['datos'][$i] ?? ''}}
                    </td>
                @endfor
                <td class="font-italic text-small">
                    {{ $netIncomeMargins['titulo'] }}
                </td>
            </tr>

            <tr>
                @php
                    $ebit = $cuadro1['EBIT'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($ebit['datos'][$i]))
                        <td>
                            ${{ $ebit['datos'][$i] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $ebit['titulo'] }}
                </td>
            </tr>

            <tr>
                @php
                    $ebitda = $cuadro1['EBITDA'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($ebitda['datos'][$i]))
                        <td>
                            ${{ $ebitda['datos'][$i] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $ebitda['titulo'] }}
                </td>
            </tr>

            <tr class="border-bottom">
                @php
                    $dilutedEps = $cuadro1['Diluted EPS'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($dilutedEps['datos'][$i]))
                        <td>
                            ${{ $dilutedEps['datos'][$i] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $dilutedEps['titulo'] }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $dilutedEpsYoY = $cuadro1['Diluted EPS - % Change YoY'];
                @endphp
                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($dilutedEpsYoY['datos'][$i]))
                        @php
                            $class = strpos($dilutedEpsYoY['datos'][$i], '-') !== false ? 'text-red': 'text-green';
                        @endphp
                    @endif
                    <td class="{{ $class ?? '' }}">
                        {{ $dilutedEpsYoY['datos'][$i] ?? ''}}
                    </td>
                @endfor
                <td class="font-italic text-small">
                    {{ $dilutedEpsYoY['titulo'] }}
                </td>
            </tr>

            <tr>
                @php
                    $sharesOut = $cuadro1['Shares Outstanding'];
                @endphp

                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($sharesOut['datos'][$i]))
                        <td>
                            ${{ $sharesOut['datos'][$i] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $sharesOut['titulo'] }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $sharesOutYoY = $cuadro1['Shares Outstanding - % Change YoY'];
                @endphp
                @for ($i = 0; $i < $countHeaders; $i++)
                    @if (isset($sharesOutYoY['datos'][$i]))
                        @php
                            $class = strpos($sharesOutYoY['datos'][$i], '-') !== false ? 'text-red': 'text-green';
                        @endphp
                    @endif
                    <td class="{{ $class ?? '' }}">
                        {{ $sharesOutYoY['datos'][$i] ?? ''}}
                    </td>
                @endfor
                <td class="font-italic text-small">
                    {{ $sharesOutYoY['titulo'] }}
                </td>
            </tr>

        </tbody>

    </table>
</div>

<br><br>


{{-- CUADRO 2 --}}

<div class="financial-balance" style="overflow: auto;">

    @php
        $cuadro2 = $data['cuadro2'];
        $countH2 = count($cuadro2['cabeceras']);
    @endphp

    <div class="vertical-text bg-yellow text-black">{{ $cuadro2['titulo'] }}</div>


    <table class="table table-borderless w-100 finance_table">

        <thead>
            <tr class="bg-yellow">
                @foreach ($cuadro2['cabeceras'] as $cabecera)
                    <th class="text-black font-weight-bold">
                        {{ $cabecera }}
                    </th>
                @endforeach
                <th></th>
            </tr>
        </thead>

        <tbody>

            @foreach ($cuadro2 as $key => $item)
                @if ($key != 'titulo' && $key != 'cabeceras')
                    <tr
                        class="{{ getBorderToTable($key, ['Total Current Assets', 'Total Current Liabilities', 'Equity', 'Total Long- Term Assets']) }}">

                        @for ($i = 0; $i < $countH2; $i++)
                            @if (isset($item['datos'][$i]))
                                <td>${{ $item['datos'][$i] }}</td>
                            @else
                            <td></td>
                            @endif
                        @endfor

                        <td class="title">
                            {{ $item['titulo'] ?? '' }}
                        </td>
                    </tr>
                @endif
            @endforeach

        </tbody>

    </table>

</div>

<br><br>

<div class="cash-flow w-100" style="overflow: auto;">

    @php
        $cuadro3 = $data['cuadro3'];
        $countH3 = count($cuadro3['cabeceras']);
    @endphp

    <div class="vertical-text bg-yellow text-black">{{ $cuadro3['titulo'] }}</div>

    <table class="table table-borderless w-100 finance_table">

        <thead>
            <tr class="bg-yellow">
                @foreach ($cuadro3['cabeceras'] as $cabecera)
                    <th class="text-black font-weight-bold">
                        {{ $cabecera }}
                    </th>
                @endforeach
                <th></th>
            </tr>
        </thead>

        <tbody>

            <tr class="border-bottom">
                @php
                    $cash = $cuadro3['Cash Flow From Operations'];
                @endphp

                @foreach ($cash['datos'] as $dato)
                    <td>{{ $dato }}</td>
                @endforeach
                <td class="text-bold title">
                    {{ $cash['titulo'] ?? '' }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $cashYoY = $cuadro3['Cash Flow From Operations - % Change YoY'];
                @endphp
                @for ($i = 0; $i < $countH3; $i++)
                    @if (isset($cashYoY['datos'][$i]))
                        @php
                            $number = floatval($cashYoY['datos'][$i]);
                            $class = $number < 0 ? 'text-red' : 'text-green';
                        @endphp
                    @endif
                    <td class="{{ $class ?? '' }} font-italic">
                        {{ $cashYoY['datos'][$i] ?? '' }}
                    </td>
                @endfor
                <td class="text-small font-italic">
                    {{ $cashYoY['titulo'] }}
                </td>
            </tr>

            <tr class="border-bottom">
                @php
                    $cashInvesting = $cuadro3['Cash Flow From Investing'];
                @endphp
                @for ($i = 0; $i < $countH3; $i++)
                    @if (isset($cashInvesting['datos'][$i]))
                        <td>{{ $cashInvesting['datos'][$i] }}</td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $cashInvesting['titulo'] }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $cashInvYoY = $cuadro3['Cash Flow From Investing - % Change YoY'];
                @endphp
                @for ($i = 0; $i < $countH3; $i++)
                    @if (isset($cashInvYoY['datos'][$i]))
                        @php
                            $number = floatval($cashInvYoY['datos'][$i]);
                            $class = $number < 0 ? 'text-red' : 'text-green';
                        @endphp
                    @endif
                    <td class="{{ $class ?? '' }} font-italic">
                        {{ $cashInvYoY['datos'][$i] ?? '' }}
                    </td>
                @endfor
                <td class="text-small font-italic">
                    {{ $cashInvYoY['titulo'] }}
                </td>
            </tr>

            <tr class="border-bottom">
                @php
                    $cashFinancial = $cuadro3['Cash Flow From Financial'];
                @endphp
                @for ($i = 0; $i < $countH3; $i++)
                    @if (isset($cashFinancial['datos'][$i]))
                        <td>{{ $cashFinancial['datos'][$i] }}</td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $cashFinancial['titulo'] }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $cashFinancialYoY = $cuadro3['Cash Flow From Financial - % Change YoY'];
                @endphp
                @for ($i = 0; $i < $countH3; $i++)
                    @if (isset($cashFinancialYoY['datos'][$i]))
                        @php
                            $number = floatval($cashFinancialYoY['datos'][$i]);
                            $class = $number < 0 ? 'text-red' : 'text-green';
                        @endphp
                    @endif
                    <td class="{{ $class ?? '' }} font-italic">
                        {{ $cashFinancialYoY['datos'][$i] ?? '' }}
                    </td>
                @endfor
                <td class="text-small font-italic">
                    {{ $cashFinancialYoY['titulo'] }}
                </td>
            </tr>

            <tr class="border-bottom">
                @php
                    $cashCapex = $cuadro3['Capital Expenditure (CAPEX)'];
                @endphp
                @for ($i = 0; $i < $countH3; $i++)
                    @if (isset($cashCapex['datos'][$i]))
                        <td>{{ $cashCapex['datos'][$i] }}</td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $cashCapex['titulo'] }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $cashCapexYoY = $cuadro3['Capital Expenditure (CAPEX) - % Change YoY'];
                @endphp
                @for ($i = 0; $i < $countH3; $i++)
                    @if (isset($cashCapexYoY['datos'][$i]))
                        @php
                            $number = floatval($cashCapexYoY['datos'][$i]);
                            $class = $number < 0 ? 'text-red' : 'text-green';
                        @endphp
                    @endif
                    <td class="{{ $class ?? '' }} font-italic">
                        {{ $cashCapexYoY['datos'][$i] ?? '' }}
                    </td>
                @endfor
                <td class="text-small font-italic">
                    {{ $cashCapexYoY['titulo'] }}
                </td>
            </tr>

            <tr class="border-bottom">
                @php
                    $freeCash = $cuadro3['Free Cash Flow'];
                @endphp
                @for ($i = 0; $i < $countH3; $i++)
                    @if (isset($freeCash['datos'][$i]))
                        <td>{{ $freeCash['datos'][$i] }}</td>
                    @else
                        <td></td>
                    @endif
                @endfor
                <td class="text-bold title">
                    {{ $freeCash['titulo'] }}
                </td>
            </tr>

            <tr class="subtitle">
                @php
                    $freeCashYoY = $cuadro3['Free Cash Flow - % Change YoY'];
                @endphp
                @for ($i = 0; $i < $countH3; $i++)
                    @if (isset($freeCashYoY['datos'][$i]))
                        @php
                            $number = floatval($freeCashYoY['datos'][$i]);
                            $class = $number < 0 ? 'text-red' : 'text-green';
                        @endphp
                    @endif
                    <td class="{{ $class ?? '' }} font-italic">
                        {{ $freeCashYoY['datos'][$i] ?? '' }}
                    </td>
                @endfor
                <td class="text-small font-italic">
                    {{ $freeCashYoY['titulo'] }}
                </td>
            </tr>

        </tbody>

    </table>

</div>





