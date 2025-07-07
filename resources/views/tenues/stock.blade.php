@extends('erhselect')
@section('content')

    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <h2><a class="btn btn-xs btn-link btn-toggle-fullwidth">
                        <i class="fa fa-arrow-left"></i></a> Gestion des tenues </h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('bienvenue') }}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Tenues</li>
                    <li class="breadcrumb-item active">Stock des tenues</li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-4 col-sm-12 text-right">

            </div>
        </div>
    </div>

    <div class="row clearfix">

        <div class="col-lg-12">

                <div class="col-md-12">
                    <div class="card">

                        <div class="header" style="margin-bottom: -15px">
                            <h2>Nombre de tenues distribuées(Nouvelle et Ancienne)</h2>
                        </div>

                        <div class="body">
                            <div class="table-responsive">
                                <table class="table center-aligned-table">
                                    <thead>
                                    <tr>
                                        <th>CASIER</th>
                                        <th>MAGASIN</th>
                                        <th>PP RAFFIA</th>
                                        <th>INJECTION</th>

                                        <th>BACHE NOIRE</th>
                                        <th>CO-EXTRUSION</th>
                                        <th>SACHET HDPE</th>
                                        <th>EXTRUSION</th>

                                        <th>STOCK AT</th>
                                        <th>MECANICIEN/<br/>PEINTRE/<br/>MACON</th>
                                        <th>CARISTE</th>
                                        <th>TECHNICIEN SURFACE</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Noire</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Grise</span></td>
                                        <td><span style="font-weight: bold; color: #000">Blouse<br/> Bleue-Jaune</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Bleue-Foncée</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Verte</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Bleue claire</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Orange</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Rouge</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Marron</span></td>
                                        <td><span style="font-weight: bold; color: #000">Blouse<br/> Bleue</span></td>
                                        <td><span style="font-weight: bold; color: #000">Blouse <br/>Bleue claire</span></td>
                                        <td><span style="font-weight: bold; color: #000">Blouse<br/> Verte</span></td>
                                    </tr>

                                    <tr>

                                        <td>
                                            <span title="Nouvelle" style="font-weight: bold; color: #d70206">
                                                52
                                            </span>
                                            /
                                            <span title="Ancien" style="font-weight: bold; color: #0000cc">
                                                52
                                            </span>
                                        </td>
                                        <td>52</td>
                                        <td>52</td>
                                        <td>52</td>

                                        <td>52</td>
                                        <td>52</td>
                                        <td>52</td>
                                        <td>52</td>

                                        <td>52</td>
                                        <td>52</td>
                                        <td>52</td>
                                        <td>52</td>

                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

        </div>

    </div>

    <div class="row clearfix">

        <div class="col-lg-12">

                <div class="col-md-12">
                    <div class="card">

                        <div class="header" style="margin-bottom: -15px">
                            <h2>Disponible en stock</h2>
                        </div>

                        <div class="body">
                            <div class="table-responsive">
                                <table class="table center-aligned-table">
                                    <tbody>

                                    <tr>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Noire</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Grise</span></td>
                                        <td><span style="font-weight: bold; color: #000">Blouse<br/> Bleue-Jaune</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Bleue-Foncée</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Verte</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Bleue claire</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Orange</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Rouge</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chasuble<br/> Marron</span></td>
                                        <td><span style="font-weight: bold; color: #000">Blouse<br/> Bleue</span></td>
                                        <td><span style="font-weight: bold; color: #000">Blouse <br/>Bleue claire</span></td>
                                        <td><span style="font-weight: bold; color: #000">Blouse<br/> Verte</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chaussure<br/> Sécurité</span></td>
                                        <td><span style="font-weight: bold; color: #000">Chaussure<br/> Ordinaire</span></td>
                                    </tr>

                                        <tr style="font-weight: bold; color: #000;">
                                            <td @if($articleStock['13']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif >{{ $articleStock['13']['quantite_en_stock'] }}</td>
                                            <td @if($articleStock['12']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif>{{ $articleStock['12']['quantite_en_stock'] }}</td>
                                            <td @if($articleStock['11']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif>{{ $articleStock['11']['quantite_en_stock'] }}</td>
                                            <td @if($articleStock['10']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif>{{ $articleStock['10']['quantite_en_stock'] }}</td>
                                            <td @if($articleStock['9']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif>{{ $articleStock['9']['quantite_en_stock'] }}</td>
                                            <td @if($articleStock['8']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif>{{ $articleStock['8']['quantite_en_stock'] }}</td>
                                            <td @if($articleStock['7']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif>{{ $articleStock['7']['quantite_en_stock'] }}</td>
                                            <td @if($articleStock['6']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif>{{ $articleStock['6']['quantite_en_stock'] }}</td>
                                            <td @if($articleStock['5']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif>{{ $articleStock['5']['quantite_en_stock'] }}</td>
                                            <td @if($articleStock['4']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif>{{ $articleStock['4']['quantite_en_stock'] }}</td>
                                            <td @if($articleStock['3']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif>{{ $articleStock['3']['quantite_en_stock'] }}</td>
                                            <td @if($articleStock['2']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif>{{ $articleStock['2']['quantite_en_stock'] }}</td>
                                            <td @if($articleStock['1']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif>{{ $articleStock['1']['quantite_en_stock'] }}</td>
                                            <td @if($articleStock['0']['quantite_en_stock'] < 50) style="font-weight: bold; color: red" @endif>{{ $articleStock['0']['quantite_en_stock'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

        </div>

    </div>

@endsection