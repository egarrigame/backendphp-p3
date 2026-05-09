{{-- 
    VISTA: COMISIONES ACUMULADAS POR MES
    Muestra tabla con año, mes y total de comisiones
--}}

@extends('layouts.app')

@section('content')
    <h1>Comisiones Acumuladas</h1>
    
    @if($comisionesPorMes->count() > 0)
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Año</th>
                    <th>Mes</th>
                    <th>Total Comisiones</th>
                </tr>
            </thead>
            <tbody>
                @php $totalGeneral = 0; @endphp
                @foreach($comisionesPorMes as $comision)
                <tr>
                    <td>{{ $comision->año }} </td>
                    <td>
                        @php
                            $nombreMes = '';
                            switch($comision->mes) {
                                case 1: $nombreMes = 'Enero'; break;
                                case 2: $nombreMes = 'Febrero'; break;
                                case 3: $nombreMes = 'Marzo'; break;
                                case 4: $nombreMes = 'Abril'; break;
                                case 5: $nombreMes = 'Mayo'; break;
                                case 6: $nombreMes = 'Junio'; break;
                                case 7: $nombreMes = 'Julio'; break;
                                case 8: $nombreMes = 'Agosto'; break;
                                case 9: $nombreMes = 'Septiembre'; break;
                                case 10: $nombreMes = 'Octubre'; break;
                                case 11: $nombreMes = 'Noviembre'; break;
                                case 12: $nombreMes = 'Diciembre'; break;
                            }
                            echo $nombreMes;
                            $totalGeneral += $comision->total;
                        @endphp
                    </td>
                    <td>{{ number_format($comision->total, 2) }} € </td>
                </tr>
                @endforeach
                <tr class="table-primary">
                    <td colspan="2"><strong>TOTAL GENERAL</strong></td>
                    <td><strong>{{ number_format($totalGeneral, 2) }} €</strong></td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="alert alert-info">No hay comisiones registradas todavía.</div>
    @endif
    
    <a href="{{ route('gestora.dashboard') }}" class="btn btn-secondary mt-3">Volver al Dashboard</a>
@endsection