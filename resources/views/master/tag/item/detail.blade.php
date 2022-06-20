@extends('layouts.home_master')
<style>
            p {
                font-family: 'Nunito', sans-serif;
            }
 </style>

@section('judul')
Detail Tag Barang Values
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('itemTagValues.index')}}">Tag-Barang-Values</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="container-fluid">

<!-- Page Heading -->
<div class="card card-primary">
    <!-- form start -->
    <form action="{{route('itemTagValues.show',[$itemTagValues->ItemID])}}" method="POST" >
        @csrf
          @method('PUT')
        <div class="card-body">

            <div class="form-group">
                <label for="title">Nama Barang</label>
                <input disabled type="text" name="cid" maxlength="200" class="form-control" 
                value="{{old('ItemName',$itemTagValues->ItemName)}}" disabled>
            </div>
            <div class="form-group">
                <label>Tag</label>
                    <select name="itemTag[]" class="select2" multiple="multiple" data-placeholder="Pilih Tag" style="width: 100%;" disabled>
                        @foreach($data as $d)
                            <option id="tagnya{{$d->ItemTagID}}" value="{{$d->ItemTagID}}"{{$d->Name == $d->ItemTagID? 'selected' :'' }}>{{$d->Name}}</option>    
                         @endforeach
                    </select>
            </div>

        </div>
        <!-- /.card-body -->
          <div class="card-footer">
            <a type="button" href="{{route('itemTagValues.index')}}" class="btn btn-primary">Kembali</a>
        </div>
    </form>
</div>

<script type="text/javascript">
$(document).ready(function() {
    var dataTag = <?php echo json_encode($dataTag); ?>;
    $.each(dataTag, function( key, value ){
        $("#tagnya"+value.ItemTagID).prop('selected',true)
    });
});
</script>
@endsection
