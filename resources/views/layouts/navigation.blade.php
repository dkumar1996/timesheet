
<script type="text/javascript">
  
</script>
<div class="container header">



<div class="row" style="background: rgb(24, 90, 154) none repeat scroll 0% 0%;">

<div class="col-md-4 col-sm-4  col-xs-6 ">


@if(Auth::admin()->check())

  @if(Auth::admin()->get()->id>1)

  {{ html_entity_decode(HTML::linkRoute('admin.index',HTML::image('images/logo.png','Logo', array('class'=>"img-responsive")))) }}

  @else

 
    {{ html_entity_decode(HTML::image('images/logo.png','Logo', array('class'=>"img-responsive")))}}
  

  @endif
@endif


</div>


<div class="col-md-8 col-sm-8 col-xs-6">

  @if(Auth::admin()->check())

      @if(Auth::admin()->get()->id>1)
        <h4 class="text-right"  style="background: #fff none repeat scroll 0 0;float: right;margin-top: 19px; padding: 10px;">
        Welcome  {{Form::label('label', Auth::admin()->get()->first_name."!", array('class'=>'usr_font'))}} {{ link_to_route('logout', 'Logout') }}</h4>
      @else
            @if(!empty($site_id))
           
            <h5 class="text-right name-label">
             <strong> @if(!empty($data->first_name))
              <span>
              Welcome {{$data->first_name}}!</span>&nbsp;, @endif
              {{DB::table('site')->where('id',$site_id)->pluck('site_name')}}  </strong></h5>
             
            @endif
      @endif
  @endif
  <div class="default-time" ></div>
</div>

</div>

<style type="text/css">
h5.name-label {
  background: #fff none repeat scroll 0 0;
  color: #000;
  float: right;
  margin: 5px 0 0;
  padding: 10px 5px;
}
.date-time {
  color: #fff;
  font-size: 20px;
  font-weight: bold;
  margin: 20px 0;
}
.calendar-container {
  background: #FCB72A none repeat scroll 0 0;
  color: #fff;
  font-size: 18px;
  font-weight: bold;
  margin: auto;
  max-width: 1000px;
  text-align: center;
  width: 100%;
}
.default-time {
  float: right;
  font-size: 17px;
  margin: 0 0 5px;
  text-align: right;
  width: 100%;
  color: #fff;
}
.fa{
  margin-right: 5px;
}

.container.header {
  font-size: 15px;
}
.img-responsive{margin-top: 0px; float: left;}


  hr
  {
    margin-top: 0;
  }

 

@media screen and (max-width:991px){
.img-responsive{margin-top: 10px;}

}
</style>


</div> 


<!-- <div class="calendar-container"><span id="show-calendar"></span>{{HTML::image('images/calendar.png')}}</div>

 -->
        
 <hr />
	 

