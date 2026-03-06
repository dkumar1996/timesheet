<hr/>
<div class="container">
<div class="row">

<div class="col-md-3"></div>

<div class="col-md-6">

<h5 class="text-center">
<span class="text-center">App Version 1.3</span><br>
@if(Auth::admin()->check())

      @if(Auth::admin()->get()->id>1)
<a class ="text-center" style="color:#999" href="http://www.5sbs.com.au/it/" >Designed by 5 Star Business Solutions</a>
@else
<a class ="text-center" style="color:#999" >Designed by 5 Star Business Solutions</a>
@endif


@endif
</h5>

</div>

<div class="col-md-3"></div>


</div>
</div>
