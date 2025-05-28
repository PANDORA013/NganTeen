@extends('layout.master')
@section('title', 'Advanced')
@section('parentPageTitle', 'Form')
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/plugins/morrisjs/morris.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/multi-select/css/multi-select.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-spinner/css/bootstrap-spinner.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-select/css/bootstrap-select.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/nouislider/nouislider.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/summernote/dist/summernote.css')}}"/>
<style>
    .input-group-text {
        padding: 0 .75rem;
    }

</style>
@stop
@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="alert alert-warning" role="alert">
            <strong>Bootstrap</strong> Better check yourself, <a target="_blank"
                href="https://getbootstrap.com/docs/4.2/components/input-group/">View More</a>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="zmdi zmdi-close"></i></span>
            </button>
        </div>
        <div class="card">
            <div class="body">
                <h2 class="card-inside-title">Form Input Produk</h2>
                <form action="{{ route('storeProduk') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="">Nama Produk</label>
                                <input type="text" name="nama_produk" class="form-control" placeholder="produk" />
                            </div>
                            <div class="form-group">
                                <label for="">Deskripsi Produk</label>
                                <input type="text" name="deskripsi_produk" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">harga produk</label>
                                <input type="text" name="harga_produk" class="form-control" placeholder="produk" />
                            </div>
                            <div class="form-group">
                                <label for="">jumlahh Produk</label>
                                <input type="text" name="jumlah_produk" class="form-control" placeholder="produk" />
                            </div>
                            <div class="form-group">
                                <label for="">Gambar Produk</label>
                                <input type="file" name="foto" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/multi-select/js/jquery.multi-select.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-spinner/js/jquery.spinner.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
<script src="{{asset('assets/plugins/nouislider/nouislider.js')}}"></script>
<script src="{{asset('assets/plugins/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('assets/js/pages/forms/dropify.js')}}"></script>
<script src="{{asset('assets/js/pages/forms/advanced-form-elements.js')}}"></script>
<script src="{{asset('assets/plugins/summernote/dist/summernote.js')}}"></script>
@stop
