@extends('layouts.app')
@section('title', 'Office Hierarchy')

@section('style')
    <style>
        .tree,
        .tree ul {
            margin: 0;
            padding: 0;
            list-style: none;
            margin-left: 10px;
        }

        .tree ul {
            margin-left: 1em;
            position: relative;
        }

        .tree ul ul {
            margin-left: 0.5em;
        }

        .tree ul:before {
            content: "";
            display: block;
            width: 0;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            border-left: 1px solid;
        }

        .tree li {
            margin: 0;
            padding: 0 1em;
            line-height: 2em;
            color: #369;
            font-weight: 700;
            position: relative;
        }

        .tree ul li:before {
            content: "";
            display: block;
            width: 10px;
            height: 0;
            border-top: 1px solid;
            margin-top: -1px;
            position: absolute;
            top: 1em;
            left: 0;
        }

        .tree ul li:last-child:before {
            background: #fff;
            height: auto;
            top: 1em;
            bottom: 0;
        }

        .indicator {
            margin-right: 5px;
        }

        .tree li a {
            text-decoration: none;
            color: #369;
        }

        .tree li button,
        .tree li button:active,
        .tree li button:focus {
            text-decoration: none;
            color: #369;
            border: none;
            background: transparent;
            margin: 0px 0px 0px 0px;
            padding: 0px 0px 0px 0px;
            outline: 0;
        }
    </style>
@endsection

@section('content')
    <div class="card" style="">
        <div class="card-header">


            {{-- <div class="panel-heading">
                <i class="fa fa-university"> </i>
                <b> New Jimma University Structure </b>
                <span class="float-right"><i class="fa fa-download"> </i> <b> <a href="{{ route('structure-pdf') }}"> Download
                            Structure(PDF)
                        </a>
                    </b>
                </span>
            </div> --}}

            <h4>Office hierarchy view</h4>

        </div>
        <div class="card-body">

            <div class="container" style="font-size:16px; margin: auto;">

                <div class="panel panel-info">

                    <div class="panel-body">
                        <p>Please click on the office to see structure!</p>
                        <div class="row justify-center align-center m-auto">

                            <div class="col-md-9 md-auto">
                                <ul id="tree1" class="tree">

                                    @foreach ($officesList as $office)
                                        <li class="closed">

                                            @if ($office->parent_office_id == null)
                                                <div class="card border-primary mb-3 "
                                                    style="max-width: 20rem; border-radius:1%; border-top-color: #0067b8; border-top-width:1px;   border-bottom-color: #0067b8; border-bottom-width:1px; border-right-color: #0067b8; border-right-width:1px; border-left-color: #0067b8; border-left-width:3px;">
                                                    <div class="card-header">
                                                        <i class="fa fa-sitemap mr-3"> </i>
                                                        <strong
                                                            title="Click box to view full structure">
                                                            <h5 style="display: inline;"> {{ $office->officeTranslations[0]->name }}</h5>
                                                        </strong>
                                                    </div>
                                                </div>
                                            @else
                                                {{ $office->officeTranslations[0]->name }}
                                            @endif

                                            @if (count($office->offices))
                                                @include('app.office_translations.manageChild', [
                                                    'childs' => $office->offices ?? '',
                                                ])
                                            @endif

                                        </li>
                                    @endforeach

                                </ul>

                            </div>


                            {{-- <div class="col-md-3">

                                <div class="card"
                                    style="margin-right:0px; border-radius:0%; border-left-color: #0067b8; border-left-width:2px;">



                                    <img src=" {{ asset('top.png') }}" style="float:right; " alt="Image" width="550"
                                        height="600" />


                                </div>
                                <small style="text-align:right;">Figure 1.1 Top Jimma University structure </small>

                            </div> --}}

                        </div>

                    </div>

                </div>



            </div>
            <a href="{{ route('office-translations.index') }}" class="btn btn-light">
                <i class="icon ion-md-return-left text-primary"></i>
                @lang('crud.common.back')
            </a>
        </div>

        <script>
            $(function() {
                $('#tree1 ul').hide(400).parent().prepend('');
                $('#tree1').on('click', 'li', function(e) {
                    e.stopPropagation();
                    $(this).children('ul').slideToggle(400);


                });
            });
        </script>

        <script>
            $.fn.extend({
                treed: function(o) {
                    var openedClass = 'fa-minus-circle';
                    var closedClass = 'fa-plus-circle';
                    if (typeof o != 'undefined') {
                        if (typeof o.openedClass != 'undefined') {
                            openedClass = o.openedClass;
                        }
                        if (typeof o.closedClass != 'undefined') {
                            closedClass = o.closedClass;
                        }
                    };
                    var tree = $(this);
                    tree.addClass("tree");
                    tree.find('li').has("ul").each(function() {
                        var branch = $(this)
                        branch.addClass('branch');
                        branch.on('click', function(e) {
                            if (this == e.target) {
                                var icon = $(this).children('i:first');
                                icon.toggleClass(openedClass + " " + closedClass);
                                $(this).children().children().toggle();
                            }
                        }) branch.children().children().toggle();
                    });
                    tree.find('.branch .indicator').each(function() {
                        $(this).on('click', function() {
                            $(this).closest('li').click();
                        });
                    });
                    tree.find('.branch>a').each(function() {
                        $(this).on('click', function(e) {
                            $(this).closest('li').click();
                            e.preventDefault();
                        });
                    });
                    tree.find('.branch>button').each(function() {
                        $(this).on('click', function(e) {
                            $(this).closest('li').click();
                            e.preventDefault();
                        });
                    });
                }
            });
            $('#tree1').treed();
            $('#tree2').treed({
                openedClass: 'fa-folder-open',
                closedClass: 'fa-folder'
            });
        </script>

        <script>
            $(function() {
                $('.genealogy-tree ul').hide();
                $('.genealogy-tree>ul').show();
                $('.genealogy-tree ul.active').show();
                $('.genealogy-tree li').on('click', function(e) {
                    var children = $(this).find('> ul');
                    if (children.is(":visible")) children.hide('fast').removeClass('active');
                    else children.show('fast').addClass('active');
                    e.stopPropagation();
                });
            });
        </script>
    @endsection
