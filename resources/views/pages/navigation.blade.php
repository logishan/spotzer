
@extends('layouts.master')

@section('styles')



@endsection

@section('content')

                            <!-- PAGE-HEADER -->
                            <div class="page-header">
                                <h1 class="page-title">Navigation</h1>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Elements</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Navigation</li>
                                </ol>
                            </div>
                            <!-- PAGE-HEADER END -->

                            <!-- ROW-1 OPEN -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Basic Nav</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <ul class="nav1">
                                                    <li class="nav-item1">
                                                        <a class="nav-link active" aria-current="page" href="javascript:void(0);">Active</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link disabled" href="javascript:void(0);">Disabled</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Nav Vertical</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <ul class="nav1 flex-column">
                                                    <li class="nav-item1">
                                                        <a class="nav-link active" aria-current="page" href="javascript:void(0);">Active</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link disabled" href="javascript:void(0);">Disabled</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Dropdown Nav</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <ul class="nav nav-pills">
                                                    <li class="nav-item m-2">
                                                        <a class="nav-link active" aria-current="page" href="javascript:void(0);">Active</a>
                                                    </li>
                                                    <li class="nav-item dropdown m-2">
                                                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" role="button"  aria-expanded="false">Dropdown</a>
                                                        <div class="dropdown-menu br-5">
                                                            <a class="dropdown-item" href="javascript:void(0);">Action</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Another action</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Something else here</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item" href="javascript:void(0);">Separated link</a>
                                                        </div>
                                                    </li>
                                                    <li class="nav-item m-2">
                                                        <a class="nav-link" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item m-2">
                                                        <a class="nav-link disabled" href="javascript:void(0);">Disabled</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Nav Tabs</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <ul class="nav nav-pills nav-fill flex-column flex-sm-row" id="tabs-text" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link mt-2 m-lg-0 border p-3 mx-1" id="tabs-text-1-tab" data-bs-toggle="tab" href="#tabs-text-1" role="tab"  aria-selected="false">UI/UX Design</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link mt-2 m-lg-0 border p-3 mx-1" id="tabs-text-2-tab" data-bs-toggle="tab" href="#tabs-text-2" role="tab" aria-selected="false">Programming</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link mt-2 m-lg-0 border active show p-3 mx-1" id="tabs-text-3-tab" data-bs-toggle="tab" href="#tabs-text-3" role="tab"  aria-selected="true">Graphic</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link mt-2 m-lg-0 border p-3 mx-1" id="tabs-text-4-tab" data-bs-toggle="tab" href="#tabs-text-4" role="tab" aria-selected="false">Developing</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link mt-2 m-lg-0 border p-3 mx-1" id="tabs-text-5-tab" data-bs-toggle="tab" href="#tabs-text-5" role="tab" aria-selected="false">Photoshop</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Icon With Title</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <ul class="nav nav-pills nav-pills-circle" id="tabs_2" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link border p-3 m-2" id="tab1" data-bs-toggle="tab" href="#tabs_2_1" role="tab" aria-selected="false">
                                                            <span class="nav-link-icon d-block"><i class="fe fe-home"></i> Home</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link border p-3 m-2" id="tab2" data-bs-toggle="tab" href="#tabs_2_2" role="tab"  aria-selected="false">
                                                            <span class="nav-link-icon d-block"><i class="fe fe-unlock"></i> Lock </span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link p-3 border active show m-2" id="tab3" data-bs-toggle="tab" href="#tabs_2_3" role="tab" aria-selected="true">
                                                            <span class="nav-link-icon d-block"><i class="fe fe-play"></i> Videos</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link border p-3 m-2" id="tab4" data-bs-toggle="tab" href="#tabs_2_3" role="tab" aria-selected="false">
                                                            <span class="nav-link-icon d-block"><i class="fe fe-layers"></i> Severs</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link border p-3 m-2" id="tab5" data-bs-toggle="tab" href="#tabs_2_4" role="tab" aria-selected="false">
                                                            <span class="nav-link-icon d-block"><i class="fe fe-image"></i> Gallery </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card icon-nav-bar">
                                        <div class="card-header">
                                            <h3 class="card-title">Icon Nav Bar</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <ul class="nav nav-pills nav-pills-circle" id="tabs_3" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link border w-50p h-50p text-center br-100 m-2" id="tab21" data-bs-toggle="tab" href="#tabs_2_1" role="tab" aria-controls="tab1" aria-selected="false">
                                                            <span class="nav-link-icon d-block text-center mx-auto"><i class="fe fe-home"></i></span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link border w-50p h-50p br-100 m-2" id="tab22" data-bs-toggle="tab" href="#tabs_2_2" role="tab" aria-controls="tab2" aria-selected="false">
                                                            <span class="nav-link-icon d-block text-center mx-auto"><i class="fe fe-unlock"></i></span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link border active show w-50p h-50p br-100 m-2" id="tab23" data-bs-toggle="tab" href="#tabs_2_3" role="tab" aria-controls="tab3" aria-selected="true">
                                                            <span class="nav-link-icon d-block text-center mx-auto"><i class="fe fe-play"></i></span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link border w-50p h-50p br-100 m-2" id="tab24" data-bs-toggle="tab" href="#tabs_2_3" role="tab" aria-controls="tab4" aria-selected="false">
                                                            <span class="nav-link-icon d-block text-center mx-auto"><i class="fe fe-layers"></i></span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link border  w-50p h-50p br-100 m-2" id="tab25" data-bs-toggle="tab" href="#tabs_2_4" role="tab" aria-controls="tab5" aria-selected="false">
                                                            <span class="nav-link-icon d-block text-center mx-auto"><i class="fe fe-image"></i> </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Color Nav</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="example">
                                                <ul class="nav1 bg-primary">
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white active" href="javascript:void(0);">Active</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link disabled text-white-50" href="javascript:void(0);">Disabled</a>
                                                    </li>
                                                </ul>
                                                <ul class="nav1 bg-secondary mt-4">
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white active" href="javascript:void(0);">Active</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link disabled text-white-50" href="javascript:void(0);">Disabled</a>
                                                    </li>
                                                </ul>
                                                <ul class="nav1 bg-success mt-4">
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white active" href="javascript:void(0);">Active</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link disabled text-white-50" href="javascript:void(0);">Disabled</a>
                                                    </li>
                                                </ul>
                                                <ul class="nav1 bg-danger mt-4">
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white active" href="javascript:void(0);">Active</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link disabled text-white-50" href="javascript:void(0);">Disabled</a>
                                                    </li>
                                                </ul>
                                                <ul class="nav1 bg-warning mt-4">
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white active" href="javascript:void(0);">Active</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link text-white" href="javascript:void(0);">Link</a>
                                                    </li>
                                                    <li class="nav-item1">
                                                        <a class="nav-link disabled text-white-50" href="javascript:void(0);">Disabled</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- COL-END -->
                            </div>
                            <!-- ROW CLOSED-->

@endsection

@section('scripts')



@endsection
