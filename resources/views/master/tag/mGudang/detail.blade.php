@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Detail Tag Gudang Values
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('tagValuesMGudang.index')}}">Tag-Gudang-Values</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('tagValuesMGudang.show',[$mGudang->MGudangID])}}" method="POST" >
        @csrf
        @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama Gudang</label>
                <input disabled type="text" name="cid" maxlength="50" class="form-control" 
                value="{{old('cidpulau',$mGudang->cname)}}">
            </div>
            <div class="form-group">
                <label>Tag</label>
                    <select name="gudangAreaSimpan[]" class="select2" multiple="multiple" data-placeholder="Pilih Tag" style="width: 100%;" disabled>
                        @foreach($data as $d)
                            <option id="tagnya{{$d->MGudangAreaSimpanID}}" value="{{$d->MGudangAreaSimpanID}}"{{$d->cname == $d->MGudangAreaSimpanID? 'selected' :'' }}>{{$d->cname}}</option>    
                         @endforeach
                    </select>
            </div>

        </div>
        <!-- /.card-body -->
          <div class="card-footer">
            <a class="btn btn-default bg-info" href="{{route('tagValuesMGudang.index')}}">
              Back
            </a>
        </div>
    </form>
</div>

<script type="text/javascript">
$(document).ready(function() {
    var dataTag = <?php echo json_encode($dataTag); ?>;
    $.each(dataTag, function( key, value ){
        $("#tagnya"+value.MGudangAreaSimpanID).prop('selected',true)
    });
});
</script>
@endsection
