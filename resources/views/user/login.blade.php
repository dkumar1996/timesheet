@extends('layouts.default_user')

@section('content')
<style type="text/css">
h4{background: }
ul{margin: 0;padding: 0}
li {
    color: red;
    list-style: outside none none;
}
.fleft {
    float: left;
    margin: 10px;
    text-align: center;
    width: auto;
}
.cross
{
	background: #e2e2e2 none repeat scroll 0 0;
    border: 1px solid #333;
    cursor: pointer;
    padding: 5px;
}
.form-control {
   
    font-size: 18px;
    height: 50px;
    width: 100%;

}
.form-group{position: relative;}

.numbers-extra,.numbers-full {
    float: left;
    padding: 5px;
    width: 33.33%;
}
.numbers-full {width: 100%}
.numbers {
    float: left;
    padding: 5px;
    width: 33.33%;
}
.number,.btn {
    border: 1px solid #999;
    border-radius: 10px;
    padding: 20px;
    width: 100%;
    font-size: 30px;
}
input[type='number'] {
    -moz-appearance:textfield;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
}

h4 {
    background: #fcb825 none repeat scroll 0 0;
    color: #fff;
    padding: 10px
}

.btn:active, .btn-primary:active  {
    background: #fcb92c none repeat scroll 0 0;
    border: 1px solid #ffffff;
    color: #ffffff;
    outline: 0 none !important;
}
#custom_formgroup{margin: 0; text-align: center; color: #FDB928; font-size: 200% ;height:40px;}
</style>
<script type="text/javascript">

	$(document).ready(function(){

		$("#spantag").on('click',function(){
             $('#show_pwd').html('');
			$('#pin').val('');

		});
		
		
		$('.number').on("touchstart", function(){
			/* var number = $(this).html();
             $("#pin").val(function() {
                return this.value + number;
            });*/
            $('#show_pwd').html($('#show_pwd').html()+'&#42;');
			$('#pin').val($('#pin').val()+$(this).val());
          
		});
        
		
		
	});
   

</script>


<div class="container">
<div class="row">
<!-- <div class="col-md-3"></div>
 -->


<div class="col-md-12 col-sm-12 col-xs-12">


	       	@if (count($errors) > 0)
                     
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li style="text-align:center">{{ $error }}</li>
                                @endforeach
                            </ul>
                       
            @endif
	       	

	       	 {{Form::open(['action'=>['UserController@store'],'Method'=>'Post','role'=>'form'])}}
	       	 
	       	 <div class="form-group" id="custom_formgroup">
             <span id="show_pwd"></span>
          
              
			{{Form::hidden('pin',null,['class'=>'form-control','id'=>'pin','required','Placeholder'=>'Enter the PIN',' readonly'=>'true'])}} 
			<!-- <span id="spantag"></span> -->
			{{Form::hidden('id',$data->id)}}
			{{Form::hidden('site_id',$site_id)}}
			</div>


<div class="password">

<div class="numbers">{{Form::button('1',['class'=>'btn number','value'=>1])}}</div>

<div class="numbers">{{Form::button('2',['class'=>'btn number','value'=>2])}}</div>

<div class="numbers">{{Form::button('3',['class'=>'btn number','value'=>3])}}</div>

<div class="numbers">{{Form::button('4',['class'=>'btn number','value'=>4])}}</div>

<div class="numbers">{{Form::button('5',['class'=>'btn number','value'=>5])}}</div>

<div class="numbers">{{Form::button('6',['class'=>'btn number','value'=>6])}}</div>

<div class="numbers">{{Form::button('7',['class'=>'btn number','value'=>7])}}</div>

<div class="numbers">{{Form::button('8',['class'=>'btn number','value'=>8])}}</div>

<div class="numbers">{{Form::button('9',['class'=>'btn number','value'=>9])}}</div>

<div class="numbers">{{Form::button('0',['class'=>'btn number','value'=>0])}}</div>

{{Form::hidden('email',$data->email)}}
<div class="numbers-extra">{{html_entity_decode(HTML::LinkRoute('user.show',Form::button('Cancel',['class'=>'btn btn-primary']),['id'=>$site_id]))}}
</div>

<div class="numbers-extra">{{Form::button('Reset',['class'=>'btn btn-primary','id'=>'spantag'])}}</div>
<div class="numbers-full">{{Form::submit($login_status,['class'=>'btn btn-primary'])}}</div>

{{Form::close()}}	

</div>

<!-- <div class="col-md-3"></div> -->

</div>
	
</div>
	
</div>
<br>
@endsection