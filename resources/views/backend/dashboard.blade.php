@extends('backend.layouts.master')
@section('title','Dashboard')
@section('content')

    <div class="layout-px-spacing">

        <div class="middle-content container-xxl p-0">

            <div class="row layout-top-spacing">

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Vendor Count</h6>
                                </div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value ">{{ tableCount('users', 'type', 'user', false) }} </p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Staff Count</h6>
                                </div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value ">{{ tableCount('users', 'type', 'admin', false) }}  </p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Delivery Boy Count</h6>
                                </div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value ">{{ tableCount('users', 'type', 'driver', false) }}  </p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Product Count</h6>
                                </div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value ">{{ tableCount('products', null, null, false) }}  </p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Orders Count</h6>
                                </div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value ">{{ tableCount('orders', null, null, false) }}  </p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Revenue</h6>
                                </div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value ">{{ totalRevenue() }} </p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection
